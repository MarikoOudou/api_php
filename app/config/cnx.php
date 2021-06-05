<?php 
	$inf = 'mysql:host=127.0.0.1;dbname=nikkibeach';
	$user = 'root';
	$mp = '';
	
	try{
		$pdo = new PDO($inf,$user,$mp);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$pdo_options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_OBJ;
	}catch(PDOException $e){
		echo 'Erreur de connexion à la base de donnée';
	}
