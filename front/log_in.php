<?php

/***
用户登陆页面
***/

define('ACC',true);
require('../include/init.php');

$user=new UserModel();
// 点击了登录按钮
if(isset($_POST['act'])){
	//print_r($_POST);exit;
	// 验证码
	$code = strtolower(trim($_POST['verification'])); 
	if($code!==$_SESSION["var_code"]){ 
	    echo "<script> alert('验证码不正确')</script>";
	    echo "<script> history.back();</script>";
	    exit;
	}

	
	// 收取信息
	$u=$_POST['username'];
	$p=$_POST['passwd'];
	// 检验是否合法会检验所有数据
	

	// 用户登录
	if ($_POST['iden']=='user') {
		// 检验用户名不存在
		if(!$user->checkUser($_POST['username'])) {
	    	echo "<script> alert('用户名不存在')</script>";
    		echo "<script> history.back();</script>";
    		exit;
		}
	
		// 核对信息
		$row = $user->checkUser($u,$p);
		if(!$user->checkUser($u,$p)){
			echo "<script> alert('登录失败')</script>";
    		echo "<script> history.back();</script>";
		}else{
			echo "<script> alert('登录成功,欢迎来到倩的网上书店')</script>";
    		echo "<script> window.location.href='index.php'</script>";
			// 把信息放入session中
			$_SESSION=$row;
			// 记住密码
			if(isset($_POST['remember'])){
				setcookie('remuser',$u,time()+14*24*60*60);
				setcookie('rempasswd',$p,time()+14*24*60*60);
			}else{
				setcookie('remuser','',0);
				setcookie('rempasswd','',0);
			}
		}
		include(ROOT . 'view/front/msg.html');
    	exit;
    }
    // 管理员登录
    else if ($_POST['iden']=='admin') {
    	if($p=='admin' && $u=='admin'){
    		echo '登录成功';
    		echo "<script> window.location.href='../admin/index.php' </script>";
    	}else{
    		echo "<script> alert('请核对管理员账号和密码')</script>";
    		echo "<script> history.back();</script>";
    		exit;
    	}
    }
    
}else {

    $remuser = isset($_COOKIE['remuser'])?$_COOKIE['remuser']:'';
    $rempasswd= isset($_COOKIE['rempasswd'])?$_COOKIE['rempasswd']:'';

    // 准备登陆
    include(ROOT . 'view/front/log_in.html');
}
