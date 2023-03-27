<?php 
if(isset($_SESSION['user'])): ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="img/logo_001.png" sizes="16x16">
	<title>webindicator | EXELCO</title>
	<link rel="stylesheet" type="text/css" href="css/styles_tab.css">
	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> -->
	<script src="js/jquery-3.4.0.min.js"></script>
	<script src="js/all.js"></script>
	<script src="js/jszip.min.js"></script>
	<script src="js/pptxgen.min.js"></script>
	<script src="js/promise.min.js"></script>
</head>
<body>
	<!-- main -->
	<ul class="main">
	<!-- form action -->
		<form class="datebox" action="content.php" method="post">
			<label for="date"> Date: </label>
			<input type="month" name="date" min="2000-01" placeholder="yyyy-mm"  value="<?php if(isset($_POST['date'])) echo $_POST['date']; else echo date('Y-m'); ?>" pattern="(?:20)[0-9]{2}-(?:0[1-9]|1[0-2])" title=" format YYYY-MM" required><button class="button full" type="submit">Search<i class="icon fas fa-search"></i></button><button class="button media" type="submit"><i class="icon fas fa-search"></i></button>
		</form> <!-- <form class="rigth" ><button class="button full" onclick="exportTableToExcel()">Export<i class="icon far fa-file-excel"></i></button></button><button class="button media" href="excel.php"><i class="icon far fa-file-excel"></i></button></form> -->
  		<form class="right" action="close.php"><span class="full"><i class="icon fa fa-user"></i><?php echo $_SESSION['name'];?></span><button class="button full">Log off<i class="icon fas fa-sign-out-alt"></i></button><button class="button media"><i class="icon fas fa-sign-out-alt"></i></button></form>
	</ul>
	<hr>
		<div id="page-loader">
		<h1>Loading page...</h1>
		<img src="img/loader.gif" alt="loader">
		</div>
</body>
</html>
<?php endif; ?>