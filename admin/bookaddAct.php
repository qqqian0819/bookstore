<?php

define('ACC',true);
require("../include/init.php");

// print_r($_POST);exit;

$goods = new GoodsModel();

if(empty($_POST['author'])){
    $_POST['author']='佚名';
}


$data = array();

$data = $goods->_facade($_POST); // 自动过滤
$data = $goods->_autoFill($data); // 自动填充




//自动商品货号
$data['book_sn'] = $goods->createSn();

// print_r($data);exit;

if(!$goods->_validate($data)) {
    echo '对不起，所输入数据不符合规范<br />';
    echo implode(',',$goods->getErr());
    exit;
}

// print_r($data);exit;

// print_r($_POST['ori_img']);

// 上传原始图片
$uptool=new UpTool();
// $uptool->setExt('docx');//动态配置文件后缀名
// $uptool->setSzie(0.2);//动态配置文件大小
$ori_img=$uptool->up('ori_img');
// ImageTool::water($ori_img,'./water.jpg');
if($ori_img){
    $data['ori_img']=$ori_img;
}else{
    echo '图片上传失败，商品发布失败';
    echo $uptool->getError();
    exit;

}


// aa.png生成中等大小goods_aa.png--300*400和浏览时的thumb_aa.png--160*220缩略图
if($ori_img) {
    $ori_img = ROOT . $ori_img; // 加上绝对路径 
    // 生成中等大小goods_aa.png--300*400
    $goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);
    if(ImageTool::thumb($ori_img,$goods_img,300,400)) {
        $data['book_img'] = str_replace(ROOT,'',$goods_img);
    }

    // 浏览时的thumb_aa.png--160*220 
    $thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);

    if(ImageTool::thumb($ori_img,$thumb_img,160,220)) {
        $data['thumb_img'] = str_replace(ROOT,'',$thumb_img);
    }

}
if($goods->add($data)) {
    echo '书籍发布成功';
} else {
    echo '书籍发布失败';
}

