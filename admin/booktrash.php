<?php
/*回收站功能
接受id。实例化。调用trash()
*/
define('ACC',true);
require("../include/init.php");



//show从html页面传过来
if(isset($_GET['act'])&&$_GET['act']=='show'){
	// 展示所有回收的已经被“删除"商品
	$book=new goodsModel();
	$booklist=$book->getTrash();//is_delete=1
	include(ROOT . 'view/admin/booktrash.html');

	/*if(isset($_GET['act'])){
		$goodslist=$goods->reTrush();
	}*/
}else{//点击回收站调用该函数。还未被“删除的商品” is_delete=0
	$book_id=$_GET['book_id']+0;
	// print_r($book_id);exit;
	$book=new GoodsModel();
	$flag=$book->trash(array('is_delete'=>1),$book_id);

	if($flag){
		echo '已加入回收站';
	}else{
		echo '加入回收站失败';
	}

}