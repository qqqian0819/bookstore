<?php
/*回收站恢复功能*/
define('ACC',true);
require("../include/init.php");

$book_id=$_GET['book_id']+0;
$book=new GoodsModel();
if($book->reTrash(array('is_delete'=>0),$book_id)){//is_delete=0
	echo '商品恢复成功';
}else{
	echo '商品恢复失败';
}