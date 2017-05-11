<?php
define('ACC',true);
require('../include/init.php');


// 调用model
$cat = new CatModel();
// 查找出所有商品
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist,0);

// print_r($catlist);

include(ROOT . 'view/admin/catelist.html');