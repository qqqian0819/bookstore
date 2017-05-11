<?php

define('ACC',true);
require('../include/init.php');

$cat=new CatModel();
$book=new GoodsModel();
// 取出树状导航
$cats = $cat->select(); // 获取所有的栏目
$sort = $cat->getCatTree($cats,0,1);//递归排序
// 取出作者
$aulist=$book->auList();

// 当前页
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page < 1) {
    $page = 1;
}
// 每页
$perpage =6;




//默认无作者
if(!isset($_GET['author'])){
	$_GET['author']='';
}
if(!isset($_GET['sort'])){
	$_GET['sort']='all';
}

// 精品
if(isset($_GET['sort'])&&$_GET['sort']=='best'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='精品';
	$total=$book->number(' is_best = 1 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$goodslist = $book->special('is_best = 1',$page,$perpage);
// 所有
}else if(isset($_GET['sort'])&&$_GET['sort']=='all'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='所有书籍';
	$allNum=$book->select();
	$total=$book->number(' is_delete=0 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;	
	$goodslist=$book->getGoods($offset,$perpage);
// 热销
}else if(isset($_GET['sort'])&&$_GET['sort']=='hot'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='热销';
	$total=$book->number(' is_hot = 1 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$goodslist = $book->special('is_hot = 1',$offset,$perpage);
// 关键字搜索
}else if(isset($_GET['sort'])&&$_GET['sort']=='search'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='';
	if(isset($_GET['keyword'])){
		$content=$_GET['keyword'];
	}else{
		$content=$_POST['keyword'];
	}
	$string=" book_name like'% " .trim($content). " %' or author like'%" . trim ($content) ."%' or book_brief like'%".trim($content)."%' or book_publish like'%" .trim($content) ."%'";
	$total=$book->number($string);
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$goodslist= $book->special($string,$offset,$perpage);
// 作者
}else if(isset($_GET['sort'])&&$_GET['sort']=='author'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='作者';
	$author=$_GET['author'];
	$total=$book->number("author='$author'");
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$goodslist = $book->special("author='$author'",$offset,$perpage);
// 出版时间升序
}else if(isset($_GET['sort'])&&$_GET['sort']=='ts'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='出版时间';
	$speSort='pub_date asc';
	$total=sizeof($book->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;	
	$goodslist= $book->speSort($speSort,$offset,$perpage);
// 出版时间降续
}else if(isset($_GET['sort'])&&$_GET['sort']=='tj'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='出版时间';
	$speSort='pub_date desc';
	$total=sizeof($book->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;	
	$goodslist= $book->speSort($speSort,$offset,$perpage);
// 价格升序
}else if(isset($_GET['sort'])&&$_GET['sort']=='ms'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='本店价';
	$speSort='shop_price asc';
	$total=sizeof($book->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;	
	$goodslist= $book->speSort($speSort,$offset,$perpage);
// 价格降续
}else if(isset($_GET['sort'])&&$_GET['sort']=='mj'){
	$nav[0]['cat_id']='';
	$nav[0]['cat_name']='本店价';
	$speSort='shop_price desc';
	$total=sizeof($book->select());
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;	
	$goodslist= $book->speSort($speSort,$offset,$perpage);
}





$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();


include(ROOT . 'view/front/column.html');