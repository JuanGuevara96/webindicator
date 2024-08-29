<?php session_start();
if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	} else {
	require 'views/header.php';
	require 'tbindicators.php';	
	include_once 'views/footer.php';
	}
 ?>