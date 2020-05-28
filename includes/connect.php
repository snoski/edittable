<?php
	include_once '../triviaLogin.php'; 
	
	$dsn= "mysql:host=$db_host;dbname=$db";
	 $options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	];
	try {
		$conn = new PDO($dsn, $db_user, $db_pw,$options);
	} catch (\PDOException $e) {
		throw new \PDOException((int)$e->getCode());
	}
	
?>