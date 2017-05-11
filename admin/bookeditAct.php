<?php
/*
作用:接收cbookedit.php表单页面发送来的数据
并调用model,把数据库入库
*/

define('ACC',true);
require('../include/init.php');

// 第一步,接数据
// print_r($_POST);exit;


// 检验数据
$goods = new GoodsModel();

if(empty($_POST['author'])){
    $_POST['author']='佚名';
}

if(empty($_POST['book_name'])) {
    exit('书名不能为空');
}


// print_r($_POST);exit;

$data = array();

$data = $goods->_facade($_POST); // 自动过滤
$data = $goods->_autoFill($data); // 自动填充



$data['author'] = $_POST['author'];

$book_id = $_POST['book_id'] + 0;
//print_r($data);exit;

// 第三步,实例化model 并调用model的相关方法
$book = new GoodsModel();




if($book->update($data,$book_id)) {
    echo '书籍信息修改成功';
    exit;
} else {
    echo '书籍信息修改失败';
    exit;
}