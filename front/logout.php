<?php

define('ACC',true);
require('../include/init.php');

session_destroy();


echo "<script> alert('退出成功。期待您的下次光临')</script>";
echo "<script> window.location.href='index.php'</script>";
exit;

