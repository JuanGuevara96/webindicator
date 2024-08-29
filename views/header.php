<?php //session_start(); 
if (!isset($_SESSION['user'])) //comprobacion si el user esta logeado
    header('Location: index.php');
if (isset($_POST['year']) && isset($_POST['month'])) {
  $_POST['date'] = $_POST['year']."-".$_POST['month'];
  $dateshow = dateshow($_POST['date']);
}
  function dateshow($date){
    //date format -> January 31, 2020
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
	<meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
	<link rel="icon" href="img/logo_001.png" sizes="16x16">
  <title>webindicator | EXELCO</title>
  
    <link rel="stylesheet" type="text/css" href="./css/style-new.css">
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
    
        <!-- DATATABLES -->
    <link rel="stylesheet" type="text/css" href="./datatables/jquery.dataTables.min.css">    
    <link href="./datatables/buttons.dataTables.min.css" rel="stylesheet"/>
    <link href="./datatables/responsive.dataTables.min.css" rel="stylesheet"/>
    
		<script src="./js/jquery-3.4.0.min.js"></script>
		<script src="./js/all.js"></script>
		<script src="./js/bootstrap.min.js"></script>
    <script src="./js/bootbox.min.js"></script>
    <script src="js/jszip.min.js"></script>
    <script src="js/pptxgen.min.js"></script>
    <script src="js/promise.min.js"></script>

</head>
<body>
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
<!--           <?php if ($_SESSION['user'] == 'admin'): ?>
            <a class="nav-link js-scroll-trigger btn btn-success btn-sm" href="./aconfig.php"><i class="fas fa-cog"></i></a>  
          <?php endif;?>
           <a class="nav-link js-scroll-trigger btn btn-success btn-sm" href="./close.php" data-toggle="tooltip"
            data-placement="left" title="Log off">
            <i class="icon fas fa-sign-out-alt"></i>
           </a> -->
         <div class="js-scroll-trigger dropdown open">
          <button class="btn btn-success"
                  type="button" id="dropdownMenu" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-bars"></i>
          </button>
          <div class="dropdown-menu" style="right: 0; left: initial;">
            <a class="dropdown-item" href="./projection.php">
              <i class="fas fa-step-forward"></i>
              Projection
            </a>
            <a class="dropdown-item" href="./presentation.php">
              <i class="fas fa-tv"></i> 
              Presentation
            </a>
            <a class="dropdown-item" href="./capture-TC.php">
              <i class="fas fa-dollar-sign"></i> 
              Currency Capture/Report
            </a>
            <a class="dropdown-item" href="./setting.php">
              <i class="far fa-chart-bar"></i> 
              ER Capture/Report
            </a>

            <div class="dropdown-divider"></div>

            <?php if ($_SESSION['user'] == 'admin'): ?>
                <a class="dropdown-item" href="./aconfig.php"><i class="icon fas fa-cogs"></i> Advanced</a>
            <?php endif;?>

            <a class="dropdown-item" href="./close.php">
              <i class="icon fas fa-sign-out-alt"></i> 
              Log Off
            </a>
          </div>
        </div>

        </div>
      </div>


</div>

</body>
</html>