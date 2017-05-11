<?php

define('ACC',true);
require('../include/init.php');

$user=new UserModel();
$username=$_SESSION['username'];
$info=$user->getUser($username);
	// print_r($info);exit;
// 点击修改按钮
if(isset($_POST['modif'])){
	// 跳转到修改页面
	include(ROOT . 'view/front/useredit.html');
	exit;
}
// 点击确认修改按钮
if(isset($_POST['edit'])){
	if($_POST['passwd']!==$_POST['repasswd']){
		$msg='密码不一致，请重新输入';		
	}else{
		$data=array();
		$data['username'] = $_POST['username'];
		$data['email'] = $_POST['email'];
		$data['passwd'] = $_POST['passwd'];
		// print_r($data);echo $info['user_id'];exit;
		$flag=$user->update($data,$info['user_id']);
		if($flag){
   			$msg='修改成功';
   			$_SESSION['username']==$_POST['username'];			
		} else {
    		$msg='修改失败，请重新输入';
			
		}
	}
	
	
	include(ROOT . 'view/front/msg.html');
	exit;
}

$OI=new OIModel();
$infolist=$OI-> userOrder($info['user_id']);
// print_r($OI-> userOrder($info['user_id']));exit;

$mess=new MessModel();
$messlist=$mess->userMess($_SESSION['user_id']);
// print_r($messlist);exit;



if(isset($_GET['order_id'])){
	echo $_GET['order_id'];
}

include(ROOT . 'view/front/user.html');