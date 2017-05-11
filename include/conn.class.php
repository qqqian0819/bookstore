<?php
	$dsn = 'odbc:Driver={SQL Server};Server=127.0.0.1;Database=bookstore;';
	$user = "sa";
	$pwd = '123456';
	try
	{
		$pdo = new PDO($dsn,$user,$pwd); 
		$pdo -> query('set names utf8');
		$sql = "select * from dbo.cjb";
	}
	catch(PDOExeception $e)
	{
		echo '数据库连接失败'.$e->getMessage();
	}
	//phpinfo();
?>