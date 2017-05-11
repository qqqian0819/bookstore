<?php

define('ACC',true);
require('../include/init.php');



// 接POST, 判断合法性。

$data = array();

if(empty($_POST['cat_name'])) {
    exit('分类名不能为空');
}
$data['cat_name'] = $_POST['cat_name'];
$data['parent_id'] = $_POST['parent_id'] + 0;

if(empty($_POST['intro'])) {
    exit('分类简介不能为空');
}
$data['intro'] = trim($_POST['intro']);

$cat_id = $_POST['cat_id'] + 0;
// print_r($cat_id);exit;

// 调用model 来更改
$cat = new CatModel();

// 查找新父栏目的家谱树。一个栏目A,不能修改成为A的子孙栏目的子栏目.
$trees = $cat->getTree($data['parent_id']);

// 判断自身是否在新父栏目的家谱树里面
$flag = true;
foreach($trees as $v) {
    if($v['cat_id'] == $cat_id) {
        $flag = false;
        break;
    }
}

if(!$flag) {
    echo '父栏目选取错误';
    exit;
}


if($cat->update($data,$cat_id)) {
    echo '修改成功';
} else {
    echo '修改失败';
}