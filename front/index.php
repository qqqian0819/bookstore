<?php

define('ACC',true);
require('../include/init.php');

// session_start();
$goods=new GoodsModel();
// 默认4条取出热销品,精品按时间升序
$hotlist=$goods->getHot();
$bestlist=$goods->getBest();

// 取出指定条数的所有物品默认取12个
$allist=$goods->getAll();


include(ROOT . 'view/front/index.html');