<?php

define('ACC',true);
require('../include/init.php');
$order=new OIModel();


// 当前页
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page < 1) {
    $page = 1;
}
// 每页
$perpage =8;


//关键字搜索
if(isset($_GET['sort'])&&$_GET['sort']=='search'){
	// echo $_POST['keyword'];exit;
	$keyword=$_POST['keyword'];
	$total= $order->number($keyword);
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$orderlist=$order->keyOrder($keyword,$offset,$perpage);
}else{
	// 默认时间降续
	if(!isset($_GET['sort'])){
		$sort='add_time desc';
	}
	// print_r($orderlist);
	// 时间升序
	else if(isset($_GET['sort'])&&$_GET['sort']=='ts'){
		$sort='add_time asc';
	}
	// 金额升序
	else if(isset($_GET['sort'])&&$_GET['sort']=='ms'){
		$sort='order_amount asc';
	}
	// 金额降续
	else if(isset($_GET['sort'])&&$_GET['sort']=='mj'){
		$sort='order_amount desc';
	}

	$total=sizeof($order->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;


	$orderlist=$order->selectAll($sort,$offset,$perpage);


}



$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();

include (ROOT. 'view/admin/orderlist.html');