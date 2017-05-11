<?php
define('ACC',true);
require('../include/init.php');


$book = new CatModel();
$booklist = $book->select();
$booklist = $book->getCatTree($booklist);
include(ROOT . 'view/admin/bookadd.html');