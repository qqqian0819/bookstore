<?php

define('ACC',true);
require('../include/init.php');
$book_id = $_GET['book_id'] + 0;

// print_r($book_id);exit;

$book=new GoodsModel();
$bookinfo=$book->find($book_id);
// print_r($bookinfo);exit;

$cat_id=$bookinfo['cat_id'];
// print_r($catinfo);
$cat = new CatModel();
$catinfo = $cat->find($cat_id);
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);
include(ROOT . 'view/admin/bookedit.html');