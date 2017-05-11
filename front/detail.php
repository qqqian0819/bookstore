<?php

define('ACC',true);
require('../include/init.php');

// 是否传参
$book_id = isset($_GET['book_id'])?$_GET['book_id']+0:0;


// 查询商品信息
$goods=new GoodsModel();
$g=$goods->find($book_id);
// 参数有问题
if(empty($g)) {
    header('location: index.php');
    exit;
}

// 浏览历史
$uri=$_SERVER['REQUEST_URI'];

// 把多个uri存放的数组转化成为字符串

if(!isset($_COOKIE['history'])){//第一次访问
	$his[]=$uri.'#'.$g['book_name'];//加uri入数组
}else{//第n次访问
	$his=explode('|',$_COOKIE['history']);//将字符串转化为数组
	//$his[]=$uri;//加uri入数组在数组的后面。

	//将最近访问的记录放在最上面
	array_unshift($his, $uri.'#'.$g['book_name']  );//array_unshift — 在数组开头插入一个或多个单元
	// 移除数组中重复
	$his=array_unique($his);//array_unique 对每个值只保留第一个遇到的键名.所以要先unshift

	// 最多存放10个历史记录
	if(count($his)>10){
		array_pop($his);//array_pop — 将数组最后一个单元弹出（出栈）
	}
	
}

// 因为cookie不能够存数组资源。需要将数组转化为字符串
setcookie('history',implode('|', $his));

/* foreach($his as $v) {
     // echo $v ;
 	// string substr ( string $string , int $start [, int $length ] )返回字符串的子串
 	// int strrpos ( string $haystack , string $needle [, int $offset = 0 ] )返回字符串 haystack 中 needle 最后一次出现的数字位置
 	echo substr($v,0,strrpos($v,'#')),'<br/>';//地址
 	echo substr($v,strrpos($v,'#')+1),'<br/>';//书名
 	echo mb_substr(substr($v,strrpos($v,'#')+1),0,7,'UTF-8'),'...','<br/>';
 	echo '<hr/>';
}


exit;*/

// 面包屑导航
$cat = new CatModel();
$nav = $cat->getTree($g['cat_id']);//查出所有栏目*/s

$mess=new MessModel();
$messlist=$mess->bookMess($book_id);
// print_r($messlist);exit;
// 添加评论
if(isset($_GET['act'])&&$_GET['act']=='add'){
	// echo $_POST['content'],$_SESSION['user_id'],$_GET['book_id'];exit;
	$data['user_id']=$_SESSION['user_id'];
	$data['book_id']=$id=$_GET['book_id'];
	$data['content']= $_POST['content'];
	if(isset($_POST['anonymous'])){
		$data['is_anonymous']=1;
	}else{
		$data['is_anonymous']=0;
	}
	$data['times']=time();
	// print_r($data);exit;
	if($mess->add($data)){
		echo "<script> alert('书籍评论成功')</script>";
        echo "<script> window.location.href='detail.php?book_id=$id'</script>";
	}else{
		echo "<script> alert('书籍评论失败')</script>";
      	echo "<script> history.back();</script>";
	}

	
}


include(ROOT . 'view/front/detail.html');