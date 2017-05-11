<?php
/*
regAct.php
作用:接收用户注册的表单信息,完成注册
*/

define('ACC',true);
require('../include/init.php');


// print_r($_POST);exit;

$user=new UserModel();


// 验证码
$code = strtolower(trim($_POST['verification'])); 
if($code!==$_SESSION["var_code"]){ 
    echo "<script> alert('验证码不正确')</script>";
    echo "<script> history.back();</script>";
    exit;
}

/*
调用自动检验功能
检验用户名4-16字符之内
email检测
passwd不能为空
*/
// 自动检验
if(!$user->_validate($_POST)) {  
    $msg=implode('<br />',$user->getErr());
    include(ROOT . 'view/front/msg.html');
    exit;
}

// 检验用户名是否已存在
if($user->checkUser($_POST['username'])) {
    echo "<script> alert('用户名已存在')</script>";
    echo "<script> history.back();</script>";
}
// 检验密码前后是否一致
if($_POST['repasswd']!==$_POST['passwd']){
	  echo "<script> alert('密码不一致')</script>";
      echo "<script> history.back();</script>";
}
// 自动填充时间
$data = $user->_autoFill($_POST);  
// 自动过滤
$data = $user->_facade($data);




if($user->reg($data)) {
    echo "<script> alert('注册成功,为您转至登录页面')</script>";
    echo "<script> window.location.href='log_in.php'</script>";
} else {
    echo "<script> alert('对不起，注册失败')</script>";
    echo "<script> history.back();</script>";
}



// 引入view
include(ROOT . 'view/front/msg.html');
