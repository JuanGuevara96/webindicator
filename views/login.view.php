<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="img/logo_001.png" sizes="16x16">
	<title>webindicator | EXELCO</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> -->
	<script src="js/all.js"></script>
	<link rel="stylesheet" href="css/styles_log.css">
</head>
<body>
	<div class="testbox">
		<img src="img/logo_001.png" class="logo">
		<hr>
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post" name="login">
			<label id="icon" for="name"><i class="icon-envelope fa fa-user"></i></label>
			<input type="text" name="user" class="user" placeholder="User">
			<label id="icon" for="name"><i class="icon-envelope fa fa-lock"></i></label>
			<input type="password" name="password" class="password" placeholder="Password">
			<div>
				<!--<a onclick='login.submit()' class="button">Ingresar</a>-->
			<button onclick='login.submit()' class="button">Login<i id="sign" class="fa fa-sign-in-alt"></i></button>
			<!--<i class="button fa fa-arrow-right" onclick='login.submit()'></i>-->
			</div>
		</form>
			<?php if(!empty($errors)): ?>
					<ul class="error">
						<?php echo $errors; ?>
					</ul>
			<?php endif; ?>

	</div>
</body>
</html>
