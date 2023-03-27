
<?php session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin')
		header('Location: ../index.php');

require "views/header2.php";
require "config/conn.php";
?>
<div class="container">
 <div class="row"> 
 	<div class="col-sm-12">
 		
	<ul class="nav nav-tabs">
	  <li class="nav-item">
	    <a class="nav-link active" data-toggle="tab" href="#mes_cierre">Cierre Mensual</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" data-toggle="tab" href="#adduser">Agregar Usuario</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#account">Agregar Cuenta</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#divisiones">Alta Divisiones</a>
	  </li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<?php $arr = squery("select section, substr(activedate, -2) activedate from db_ctl"); ?>
	  <div class="tab-pane fade active show" id="mes_cierre">
	  	<h2>Cierre del Mes
	  	</h2>
	  	<div class="col-md-10">
	  		<form id="frm_activedates" method="post">	
	    <button id="btn_unlock" type="submit" class="btn btn-success float-right my-2 w-25"><i class="fas fa-lock-open"></i></button>
	  		<?php 
				for ($j=0; $j < count($arr); $j++):
					$sec = strtoupper($arr[$j]['section']);
					$activedate = $arr[$j]['activedate']; ?>
					<div class='input-group input-group-lg'>
						<label class='form-control w-50'><?php echo $sec;?>
						<input name="sections[]" hidden="true" value="<?php echo $sec;?>">
						</label>

				    <select name="months[]" class="custom-select text-center w-25"> 
				    	<?php for ($i=1; $i <= 12; $i++) { //todos los meses
				    	echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
				    	if ($activedate == $i) echo "selected='1'";
				    	echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
				    </select>
				    <select name="years[]" class="custom-select text-center"> 
				      <option> <?php echo date('Y', strtotime('-1 year'));?></option>
				      <option selected="1"><?php echo date('Y');?></option>
				      <option><?php echo date('Y', strtotime('+1 year'));?></option>
				    </select>
					</div>
			<?php
				endfor;	  			
	  		 ?>
	  		</form>
	  	</div>
	  </div> <!-- fin tab cierre -->
	  <div class="tab-pane fade" id="adduser">
		<h2>Agregar Usuario</h2>
		<div class="container">
			<form id="frm_register" method="post" class="form-group row" autocomplete="off">
			<div class="col-md-4">	
				<div class="form-group">
					<label>ID</label>
					<input class="form-control" type="text" name="ID" disabled="true">
				</div>
				<div class="form-group">
					<label>Nombre</label>
					<input class="form-control" type="text" name="nombre" placeholder="name" maxlength="64" autocomplete="new-text" >
				</div>
				<div class="form-group">
					<label>Usuario</label>
					<input class="form-control" type="text" name="user" maxlength="10" autocomplete="new-text" placeholder="user" required="">
				</div>
				<div class="form-group">
					<label>Contraseña</label>
					<input id="pwd" class="form-control" type="password" name="pass" maxlength="30" autocomplete="new-password" placeholder="password" required="">
					<div class="custom-control custom-switch">
					  <input type="checkbox" class="custom-control-input" id="showpwd" onclick="showpass()">
					  <label class="custom-control-label" for="showpwd">Show Password</label>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<label>Divisiones</label>
			<?php 
				for ($i=0; $i < count($arr); $i++) { 
					$sec = substr($arr[$i]['section'], 0,3);
					echo "<div class='custom-control custom-checkbox m-2'>
						<input id='".$sec."' class='custom-control-input' type='checkbox' name='sections[]' value='".$sec ."'/>
						<label for='".$sec."' class='custom-control-label'>".$arr[$i]['section']."
						</label></div>";
				}
			?>

			</div>
			<div class="col-md-6 m-4">
				<button id="btn_user" type="submit" class="btn btn-success btn-md btn-block"> Agregar <i class="fas fa-user-plus"></i></button>
			</div>
			</form>
		</div>	
	  </div> <!-- fin tab adduser -->
	  <div class="tab-pane fade" id="account">
	  	<h2>Agregar Cuenta</h2>
		<div class="container">
		  <form id="frm_account" method="post" class="form-group row" autocomplete="off">
			<div class="col-md-10">
				<div class="form-group">
					<label>Clave Empresa</label>
					<input class="form-control" type="text" name="idcompany">
				</div>
				<div class="form-group">
					<label>Cuenta</label>
					<input class="form-control" type="text" name="account">
				</div>
				<div class="form-group">
					<label>Categoria</label>
					<select class="custom-select w-25" name="type">
					</select>
				</div>
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
				Something&nbsp;
				  <label class="btn btn-success active">
				    <input type="radio" name="options" id="option1" autocomplete="off" checked> +
				  </label>
				  <label class="btn btn-success">
				    <input type="radio" name="options" id="option2" autocomplete="off"> -
				  </label>
				</div>
				<div class="btn-group btn-group-toggle" data-toggle="buttons">
				Presupuesto&nbsp;
				  <label class="btn btn-success active">
				    <input type="radio" name="options" id="option1" autocomplete="off" checked> si
				  </label>
				  <label class="btn btn-success">
				    <input type="radio" name="options" id="option2" autocomplete="off"> no
				  </label>
				</div>
			</div>
		  </form>
		</div>
	  </div> <!-- fin tab account -->
	  <div class="tab-pane fade" id="divisiones">
	  	<h2>Agregar division</h2>
		<div class="container">
			<form id="frm_divs" method="post" class="form-group row">
				<div class="col-md-6">
					<div class="form-group">
						<label>Nombre division</label>
						<input class="form-control" type="text" name="div">
					</div>
				</div>
			</form>
		</div>
	  </div>
	</div> <!-- fin #myTabContent -->
 	</div>	
 </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$("#frm_register").submit(function(e) {
	      e.preventDefault();
	      register();
	    });
	    $("#frm_activedates").submit(function(e) {
	      e.preventDefault();
	      activedate();
	    });
	});

	function register(){
	$("#btn_user").prop("disabled", true);
	  $.ajax({
	      url: "config/register.php",
	      method: "post",
	      global: false,
	      data: $("#frm_register").serialize(),
	      dataType: "text",
	      success: function(strmsg) {
	      	custombox(strmsg);
	      },
	      complete: function() {
			$("#btn_user").prop("disabled", false);
	      }
  	  });
	}

	function activedate(){
		$.ajax({
	      url: "op/er.php",
	      method: "post",
	      global: false,
	      data: $("#frm_activedates").serialize() + "&er=activedate"+"&year= &month= ",
	      dataType: "text",
	      success: function(strmsg) {
	      	if (strmsg != "")
	      	custombox(strmsg);
	      	else
	      	custombox("Cambios realizados!");
	      }
  	  });
	}

	function custombox(msg){
		bootbox.alert({
		    message: msg,
		    buttons: {
		        ok: {
		            className: 'btn-success'
		        }
		    }
		})
	}

	function showpass() {
	  var x = document.getElementById("pwd");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	}
</script>
<?php 
//include_once "views/footer.view.php";
 ?>