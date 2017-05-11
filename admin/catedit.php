<?php

define('ACC',true);
require('../include/init.php');
$cat_id = $_GET['cat_id'] + 0;

// print_r($cat_id);exit;

$cat = new CatModel();
$catinfo = $cat->find($cat_id);

// print_r($catinfo);
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);
include(ROOT . 'view/admin/catedit.html');