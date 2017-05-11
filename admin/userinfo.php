<?php
define('ACC',true);
require('../include/init.php');

$id=$_GET['user_id'];
$user=new UserModel();
$info=$user->find($id);
// print_r($info);exit;

// $OG=new OGModel();
// $infolist=$OG->getOrder($_GET['id']);

$OI=new OIModel();
$infolist=$OI->userOrder($id);
// print_r($infolist);exit;

$mess=new MessModel();

$messlist=$mess->userMess($id);
// print_r($messlist);exit;


include(ROOT . 'view/admin/userinfo.html');