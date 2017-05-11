<?php

define('ACC',true);
require('../include/init.php');

//设置动作参数，判断用户下订单写地址提交情况购物车等动作
$act=isset($_GET['act'])?$_GET['act']:'buy';//默认buy

// 获得单例购物车实例
$cart=CartTool::getCart();

// 获得goods实例
$goods=new GoodsModel();
// 加入购物车
if($act=='buy'){
	// echo $_POST['book_number'],$_POST['total'];exit;
	$book_id=isset($_GET['book_id'])?$_GET['book_id']+0:0;
	// print_r($book_id);exit;
	$num=isset($_POST['book_number'])?$_POST['book_number']+0:1;//默认数量1
	if($book_id){//为真，即有参数
		$g=$goods->find($book_id);
		if(!empty($g)){//数据库中有此商品
			//此商品存在于回收站或者已下架
			if($g['is_delete']==1){
				$msg='该商品现已经下架。如果您需要，请联系客服';
				include(ROOT.'view/front/msg.html');
				exit;
			} 
			//先加入购物车
			$cart->addItem($book_id,$g['book_name'],$g['shop_price'],$g['market_price'],$g['author'],$num);
			// 判断库存
            $items = $cart->all();
            if($items[$book_id]['num']>$g['book_number']){
            	// 撤回刚才添加的数量
            	$cart->decNum($book_id,$num);
            	$msg='此商品库存不够.你可以联系客服';
            	include(ROOT.'view/front/msg.html');
				exit;
            }
		}
	}
	// 取出购物车中的商品
	$items = $cart->all(); 
	// print_r($cart->all());

	// 取车购物车中商品的详细信息
	$items=$goods->getCartGoods($items);

	// 购物车中该店商品总价
	$total=$cart->getPrice();
	// 购物车中市场总价
	$market_total=0.00;
	foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }
    // 节省金额
    $discount = $market_total - $total;
    // 比例round(x---要舍入的数字,prec---小数点后的位数)
    if($total==0){
    	$rate = 0;
	}else{
		$rate=round(100 * $discount/$total,2);
	}

	include(ROOT . 'view/front/cart.html');



}else if($_GET['act']=='clear'){
	$cart->clear();
	$msg='购物车已经清空';
	include(ROOT.'view/front/msg.html');
}else if($_GET['act']=='tijiao'){
	$items = $cart->all(); // 取出购物车中的商品
	// 取车购物车中商品的详细信息
	$items=$goods->getCartGoods($items);

	// 购物车中该店商品总价
	$total=$cart->getPrice();
	// 购物车中市场总价
	$market_total=0.00;
	foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }
    // 节省金额
    $discount = $market_total - $total;
    // 比例round(x---要舍入的数字,prec---小数点后的位数)
    $rate = round(100 * $discount/$total,2);
	include(ROOT.'view/front/order.html');
// 订单入库
}else if($_GET['act']=='done'){
	if($_POST['site']==0){
		$site='大学城'.$_POST['school'];
	}
	if($_POST['site']==1){
		$site='沙坪坝区'.$_POST['school'];
	}
	if($_POST['site']==2){
		$site='南岸区'.$_POST['school'];
	}
	if($_POST['site']==3){
		$site='其它'.$_POST['school'];
	}
	// print_r($_POST);echo $site ;exit;
	$OI=new OIModel();
	// 数据检验，报错退出
	if(!$OI->_validate($_POST)){
		$msg=implode(',', $OI->getErr());
		include(ROOT.'view/front/msg.html');
		exit;
	}

	// 自动过滤

	$data=$OI->_facade($_POST);

	// 自动填充
	$data=$OI->_autoFill($data);
	// 写入总金额
	$total=$data['order_amount']=$cart->getPrice();
	// 写入地址
	$data['address']=$site;
	// 写入用户信息
	$data['user_id']=isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
	// $data['username']=isset($_SESSION['username'])?$_SESSION['username']:'匿名';
	// 写入订单号
	$order_sn=$data['order_sn']=$OI->orderSn();
	// 入orderinfo表
	if(!$OI->add($data)){
		$msg='下订单失败';
		include(ROOT.'view/front/msg.html');
		exit;
	}
	// echo 'success';
	// 获取刚刚产生的orderid的值
	$order_id=$OI->insert_id();
	// 返回购物车中所有商品
	$items=$cart->all();
	// 获取ordergoods表的操作model
	$OG =new OGModel();
	$cnt=0;	// 1个订单n条商品，必须n条商品均加入成功才算成功
	// 循环订单中的商品写入ordergoods表
	foreach($items as $k=>$v){
		$data=array();
		$data['order_sn']=$order_sn;
		$data['order_id']=$order_id;
		$data['book_id']=$k;		
		$data['book_number']=$v['num'];
		$data['shop_price']=$v['price'];
		$data['subtotal']=$v['price']*$v['num'];
		// print_r($data);echo '<br />';exit;
		if($OG->addOG($data)){
			// 成功插入一条则加1
			$cnt+=1;
		}
	}
	//全部入库不成功
	if(count($items)!==$cnt){
		// 撤销此订单
		$OI->invoke($order_id);
		$msg='下订单失败';
        include(ROOT.'view/front/msg.html');
		exit;
	}

	// success 清空购物车
	$cart->clear();
	include(ROOT.'view/front/success.html');
}
