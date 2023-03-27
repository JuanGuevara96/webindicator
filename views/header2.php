<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>
		
	</title>
    <!-- <link rel="stylesheet" type="text/css" href="css/style.css"> -->
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<script src="./js/jquery-3.4.0.min.js"></script>
		<script src="./js/all.js"></script>
		<script src="./js/bootstrap.js"></script>
    <script src="./js/bootbox.min.js"></script>
    <script src="js/jszip.min.js"></script>
    <script src="js/pptxgen.min.js"></script>
    <script src="js/promise.min.js"></script>
</head>
<body>
	<style type="text/css">
		
.logo {
    border-radius: 15%;
    width: 40px;
    display: block;
}

.header{
  height: 5em;
}

#mainNav {
  background-color: rgb(187,141,23);
}

#mainNav .navbar-toggler {
  font-size: 12px;
  right: 0;
  padding: 13px;
  text-transform: uppercase;
  color: white;
  border: 0;
  background-color: green;
  font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
}

#mainNav .navbar-brand {
  color: white;
  font-family:Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
  display: flex;
  align-items: center;
  font-weight: 800;
  font-size: 24px;
}

#mainNav .navbar-brand.active, #mainNav .navbar-brand:active, #mainNav .navbar-brand:focus, #mainNav .navbar-brand:hover {
  color: green;
}

#mainNav .navbar-nav .nav-item .nav-link {
  font-size: 90%;
  font-weight: 400;
  padding: 0.75em 0;
  letter-spacing: 1px;
  color: white;
  font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
}

#mainNav .navbar-nav .nav-item .nav-link.active, #mainNav .navbar-nav .nav-item .nav-link:hover {
  color: green;
}

@media (min-width: 992px) {
  #mainNav {
    padding-top: 10px;
    padding-bottom: 10px;
    transition: padding-top 0.3s, padding-bottom 0.3s;
    border: none;
    /*background-color: transparent;*/
  }
  #mainNav .navbar-brand {
    font-size: 1.75em;
    transition: all 0.3s;
  }
  #mainNav .navbar-nav .nav-item .nav-link {
    padding: .4em !important;
  }
}
	</style>
<div class="header">
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
	    <div class="container">
	      <a class="navbar-brand js-scroll-trigger" href="./index.php"><img src="./img/logo_001.png" class="logo">&nbsp;Exelco</a>
	      

        <div class="row text-light">
          <span id="dateshow" class="nav-link js-scroll-trigger">
            <i class="icon fas fa-calendar-alt"></i> 
            <?php if(!isset($dateshow)) echo date('F j, Y'); else echo $dateshow; ?>
          </span>
           <span class="nav-link js-scroll-trigger" id="username">
            <i class="icon fa fa-user"></i> 
            <?php echo $_SESSION['name'];?>
          </span>
           <a class="nav-link js-scroll-trigger btn btn-success btn-sm" href="./close.php" data-toggle="tooltip"
            data-placement="left" title="Log off">
            <i class="icon fas fa-sign-out-alt"></i>
           </a>
        </div>
 
        </ul>
      </div>
	    </div>
	</nav>
</div>

</body>
</html>