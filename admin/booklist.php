<?php
define('ACC',true);
require('../include/init.php');

// 分类
$cat = new CatModel();
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);

$book = new GoodsModel();

// 作者
$aulist=$book->auList();
// print_r($aulist);exit;

// 当前页
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page < 1) {
    $page = 1;
}
// 每页
$perpage =8;

//所有
if(isset($_GET['sort'])&&$_GET['sort']=='all'){
	$allNum=$book->select();
	$total=$book->number(' is_delete=0 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist=$book->getGoods($offset,$perpage);
// 热销
}else if(isset($_GET['sort'])&&$_GET['sort']=='hot'){
	$total=$book->number(' is_hot = 1 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist = $book->special('is_hot = 1',$offset,$perpage);
// 精品
}else if(isset($_GET['sort'])&&$_GET['sort']=='best'){
	$total=$book->number(' is_best = 1 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist = $book->special('is_best= 1',$offset,$perpage);
// 紧缺
}else if(isset($_GET['sort'])&&$_GET['sort']=='rare'){
	$total=$book->number(' book_number <= 3 ');
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist = $book->special('book_number <= 3',$offset,$perpage);
// 作者
}else if(isset($_GET['sort'])&&$_GET['sort']=='author'){
	$author=$_GET['author'];
	$total=$book->number("author='$author'");
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist = $book->special("author='$author'",$offset,$perpage);
// 分类
}else if(isset($_GET['cat_id'])&&$_GET['sort']=='cat'){
	$cat_id=$_GET['cat_id'];
	// echo $cat_id;exit;
	$total=$book->catGoodsCount($cat_id);
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist = $book->catGoods($cat_id,$offset,$perpage);
}

// 关键字搜索
if(isset($_GET['sort'])&&$_GET['sort']=='search'){
	$content=$_POST['keyword'];
	/*"select * from tb_menus where menuname like'%".trim($content)."%' or introduce like'%".trim($content)."%' order by price asc "*/
	$string="book_name like'% " .trim($content). " %' or author like'%" . trim ($content) ."%' or book_publish like'%".trim($content)."%'";
	$total=$book->number($string);
	if($page > ceil($total/$perpage)) {
	    $page = 1;
	}
	$offset = ($page-1)*$perpage;
	$booklist= $book->special($string,$offset,$perpage);
}




$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();



include(ROOT . 'view/admin/booklist.html');