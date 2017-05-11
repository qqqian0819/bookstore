<?php

define('ACC',true);
require('../include/init.php');
$user=new Usermodel;
if(isset($_GET['id'])){
	$id=$_GET['id'];
	// echo $id;exit;
	$user=$user->find($id);
	// print_r($userlist);exit;
	include (ROOT. 'view/admin/userinfo.html');
}else{
	$userlist=$user->select();
	// print_r($userlist);
	include (ROOT. 'view/admin/userlist.html');
}


