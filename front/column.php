<?php
define('ACC',true);
require('../include/init.php');







if(!isset($_GET['author'])){
	$_GET['author']='';
}
if(!isset($_GET['sort'])){
	$_GET['sort']='';
}


// cat_id是否传入
$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']+0:0;
// print_r($cat_id);exit;
$page = isset($_GET['page'])?$_GET['page']+0:1;
if($page <1){
	$page=1;
}

$goodsModel = new GoodsModel();
$total = $goodsModel->catGoodsCount($cat_id);

// 每页取4条
$perpage = 6;

if($page > ceil($total/$perpage)) {
    $page = 1;
}

$offset = ($page-1)*$perpage;


$pagetool = new PageTool($total,$page,$perpage);
$pagecode = $pagetool->show();


$cat=new CatModel();
$category = $cat->find($cat_id);
// 如果cat_id不存在
/*if(empty($category)) {
    header('location: index.php');
    exit;
}*/


// 取出树状导航
$cats = $cat->select(); // 获取所有的栏目
$sort = $cat->getCatTree($cats,0,1);//递归排序

// 取出面包屑导航
$nav = $cat->getTree($cat_id);




// 取出栏目下的商品

$goods = new GoodsModel();
// 取出作者
$aulist=$goods->auList();

$goodslist = $goods->catGoods($cat_id,$offset,$perpage);


// print_r($goodslist);

include(ROOT . 'view/front/column.html');
