<?php
define('ACC',true);
require('../include/init.php');

$book=new GoodsModel();
$allNum=$book->select();//所有分类
$hot=$book->special('is_hot = 1');//热销
$best=$book->special('is_best = 1');//精品
$rare=$book->special('book_number <= 3');//紧缺



/*$hot=count($book->hot());
echo $hot;*/
include(ROOT . 'view/admin/main.html');