<?php session_start();
if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	} else {
	require 'views/header.view.php';
	require 'tables.php';	
	include_once 'views/footer.view.php';
	}
 ?>