<?php

define('ACC',true);
require('../include/init.php');

// echo $_GET['id'],$_GET['user_id'];exit;

$OI=new OIModel();
$info=$OI->findDet($_GET['id']);
 // print_r($info);exit;
 // echo $_GET['id'];exit;
$OG=new OGModel();
$infolist=$OG->getOrder($_GET['id']);
// print_r($infolist);exit;

include (ROOT. 'view/admin/orderinfo.html');