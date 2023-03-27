<?php //session_start(); 
if (!isset($_SESSION['user'])) //comprobacion si el user esta logeado
		header('Location: index.php');
if (isset($_POST['year']) && isset($_POST['month'])) {
	$_POST['date'] = $_POST['year']."-".$_POST['month'];
	$dateshow = dateshow($_POST['date']);
}
	function dateshow($date){
		//date to month or diary
		if (date('Ym') == date('Ym',strtotime($date))) {
			return date('F j, Y');
		}
		else {
			return date('F t, Y', strtotime($date));
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="img/logo_001.png" sizes="16x16">
	<title>webindicator | EXELCO</title>
<!-- 	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css"> -->
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!-- <link rel="stylesheet" type="text/css" href="css/styles_tab.css"> -->
	<!-- <link rel="stylesheet" type="text/css" href="css/styles_proy.css"> -->
	<script src="js/jquery-3.4.0.min.js"></script>
	<script src="js/all.js"></script>
	<script src="js/jszip.min.js"></script>
	<script src="js/pptxgen.min.js"></script>
	<script src="js/promise.min.js"></script>
</head>
<body>
	<ul class="main">
	<!-- form action -->
		<a id="home" href="./"><img class="logo-tittle" src="img/logo_001.png">EXELCO</a>		
  		<form class="right" action="close.php"><span id="dateshow"><i class="icon fas fa-calendar-alt"></i><?php if(!isset($dateshow)) echo date('F j, Y'); else echo $dateshow; ?></span><span id="username"><i class="icon fa fa-user"></i><?php echo $_SESSION['name'];?></span><?php if ($_SESSION['user'] == 'admin'): ?><a class="button" href="aconfig.php"><i class="fas fa-cog"></i></a> <?php endif; ?><button class="button full">Log off<i class="icon fas fa-sign-out-alt"></i></button><button class="button media"><i class="icon fas fa-sign-out-alt"></i></button></form>
	</ul>


	<hr class="header-hr">
	<div id="page-loader">
		<h1>Loading page...</h1>
		<img src="img/loader.gif" alt="loader">
	</div>
