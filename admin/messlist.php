<?php

define('ACC',true);
require('../include/init.php');

$mess=new MessModel();

// 当前页
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page < 1) {
    $page = 1;
}
// 每页
$perpage =6;


if(!isset($_GET['sort'])){
	$_GET['sort']='tj';
}

if(isset($_GET['sort'])&&$_GET['sort']=='ts'){
	$sort="asc";
	$total=sizeof($mess->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;

	$allMess=$mess->getMess($sort,$offset,$perpage);
}else if(isset($_GET['sort'])&&$_GET['sort']=='tj'){
	$sort="desc";
	$total=sizeof($mess->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;

	$allMess=$mess->getMess($sort,$offset,$perpage);
}

if(isset($_GET['act'])&&$_GET['act']=='search'){
	$str=$_POST['keyword'];
	// exit;
	$allMess=$mess->keyWord($str);
	$total=sizeof($allMess);
	// print_r($keyMess);exit;
}


$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();

// print_r($allMess);exit;
include (ROOT. 'view/admin/messlist.html');