
<?php session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin')
		header('Location: ../index.php');

require "views/header.php";
require "config/conn.php";
?>
<div class="container">
 <div class="row"> 
 	<div class="col-sm-12">

 	<!-- titulos de pestañas	 -->
	<ul class="nav nav-tabs">
	  <li class="nav-item">
	    <a class="nav-link active" data-toggle="tab" href="#mes_cierre">Cierre Mensual</a>
	  </li>
	  <li class="nav-item">
	    <a class="nav-link" data-toggle="tab" href="#adduser">Agregar Usuario</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#permisos">Agregar Permisos</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#account">Agregar Cuenta</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#divisiones">Alta Divisiones</a>
	  </li>
	  <li class="nav-item">
	  	<a class="nav-link" data-toggle="tab" href="#companies">Alta Compañias</a>
	  </li>
	</ul>

	<div id="myTabContent" class="tab-content">
		<?php //$arr = squery("select section_name section, activedate from sections"); ?>
		<?php $arr = squery("select section, activedate from db_ctl"); ?>

	  <div class="tab-pane fade active show" id="mes_cierre">
	  	<div class="d-flex flex-row justify-content-between m-4">
	  		<h2>Cierre del Mes</h2>
	  	</div>
	  	<div class="col-md-10">
	  		<form id="frm_activedates" method="post">	
	    <button id="btn_unlock" type="submit" class="btn btn-success float-right my-2 w-25"><i class="fas fa-lock-open"></i></button>
	  		<?php 
				for ($j=0; $j < count($arr); $j++):
					$sec = strtoupper($arr[$j]['section']);
					$activedate = $arr[$j]['activedate']; 
					$datepast = date('Y', strtotime('-1 year'));
					$datecurrent = date('Y');
					$datenext = date('Y', strtotime('+1 year'));
					?>
					<div class='input-group input-group-lg'>
						<label class='form-control w-50'><?php echo $sec;?>
						<input name="sections[]" hidden="true" value="<?php echo $sec;?>">
						</label>

				    <select name="months[]" class="custom-select text-center w-25"> 
				    	<?php for ($i=1; $i <= 12; $i++) { //todos los meses
				    	echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
				    	if (substr($activedate, -2) == $i) echo "selected='1'";
				    	echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
				    </select>
				    <select name="years[]" class="custom-select text-center"> 
				      <option <?php if($datepast == substr($activedate, 0, 4)) echo 'selected="1"';?> > <?php echo $datepast; ?></option>
				      <option <?php if($datecurrent == substr($activedate, 0, 4)) echo 'selected="1"';?>><?php echo $datecurrent; ?></option>
				      <option <?php if($datenext == substr($activedate, 0, 4)) echo 'selected="1"';?>><?php echo $datenext; ?></option>
				    </select>
					</div>
			<?php
				endfor;  			
	  		 ?>
	  		</form>
	  	</div>
	  </div> <!-- fin tab cierre -->
	  <div class="tab-pane fade" id="adduser">
	  	<div class="d-flex flex-row justify-content-between m-4">
			<h2>Agregar Usuario</h2>
			<div>
				<button class="btn btn-success" onclick="showfrm(true)"><i class="fas fa-plus-circle"></i></button>
				<button id="btn_users" class="btn btn-warning" onclick="showfrm(true)"><i class="fas fa-pen fa-inverse"></i></button>
				<button class="btn btn-danger"><i class="fas fa-times"></i></button>
				<button class="btn btn-primary"><i class="fas fa-check"></i></button>
				<button class="btn btn-secondary" onclick="showfrm(false)">Cancel</button>
			</div>
	  	</div>
		<div class="container">
			<div class="row">
			<form id="frm_register" method="post" class="form-group col-md-12 row" autocomplete="off">
			<div class="col-md-6">	
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
				<button id="btn_user" type="submit" class="btn btn-success btn-md btn-block"> Agregar <i class="fas fa-user-plus"></i></button>
			</div>
<!-- 			<div class="col-md-2">
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
			</div> -->
			</form>
			<!-- codigo datatable -->
			<div class="col-md-12 table-responsive" id="listuser">
                <table id="tbusers" class="table table-striped table-bordered table-condensed">
                  <thead>
                    <th>User</th>
                    <th>Name</th>
                    <th>Privilegies</th>
                    <th>Visits</th>
                    <th>Last Login</th>
                  </thead>
                  <tbody>                            
                  </tbody>
                </table>
            </div>
           <!-- fin datatable -->
		  	</div> <!-- /row -->
		</div>	<!-- /container -->
	  </div> <!-- fin tab adduser -->

	  <!-- inicio tab permisos -->
	  	  <div class="tab-pane fade" id="permisos">
	  	<div class="d-flex flex-row justify-content-between m-4">
	  		<h2>Agregar Permisos</h2>
	  	</div>	
		<div class="container">
			<form id="frm_permisos" method="post" class="form-group row">
			
			<div class="col-md-2">
				<label>Divisiones</label>
			<?php 
				for ($i=0; $i < count($arr); $i++) { 
					$sec = substr($arr[$i]['section'], 0,3);
					echo "<div class='custom-control custom-checkbox m-2'>
						<input id='".$sec."' class='custom-control-input' type='checkbox' name='sections[]' value='".$sec ."'/>
						<label for='".$sec."' class='custom-control-label'>".$arr[$i]['section']."
						</label></div>";
				} unset($arr);
			?>
			</div>

				<!-- codigo datatable -->
				<div class="col-lg-8 table-responsive" id="listadoregistros">
                    <table id="tbpermisos" class="table table-striped table-bordered table-condensed">
                      <thead>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Permiso</th>
                      </thead>
                      <tbody>                            
                      </tbody>
                    </table>
                </div>
                <!-- fin datatable -->
			</form>
		</div>
	  </div> <!-- /tab-pane permisos -->

	  <div class="tab-pane fade" id="account">
	  	<div class="d-flex flex-row justify-content-between m-4">
	  		<h2>Agregar Cuenta</h2>
	  	</div>
		<div class="container">
		  <form id="frm_account" method="post" class="form-group row" autocomplete="off">
			<div class="col-md-4">
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
			<!-- codigo datatable -->
			<div class="col-md-8 table-responsive" id="listaccount">
                <table id="tbaccounts" class="table table-striped table-bordered table-condensed">
                  <thead>
                    <th>Company</th>
                    <th>Account</th>
                    <th>op</th>
                    <th>Category</th>
                    <th>Budget</th>
                  </thead>
                  <tbody>                            
                  </tbody>
                </table>
            </div>
           <!-- fin datatable -->
		  </form>
		</div>
	  </div> <!-- fin tab account -->

	  <div class="tab-pane fade" id="divisiones">
	  	<div class="d-flex flex-row justify-content-between m-4">
	  		<h2>Agregar division</h2>
	  	</div>	
		<div class="container">
			<form id="frm_divs" method="post" class="form-group row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Nombre division</label>
						<input class="form-control" type="text" name="div">
					</div>
				</div>
				<!-- codigo datatable -->
				<div class="col-lg-8 table-responsive" id="listadoregistros">
                    <table id="tbsections" class="table table-striped table-bordered table-condensed">
                      <thead>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                      </thead>
                      <tbody>                            
                      </tbody>
                    </table>
                </div>
                <!-- fin datatable -->
			</form>
		</div>
	  </div> <!-- /tab-pane divisiones -->

	  	  <div class="tab-pane fade" id="companies">
	  	<div class="d-flex flex-row justify-content-between m-4">
	  		<h2>Agregar Compañia</h2>
	  	</div>	
		<div class="container">
			<form id="frm_comp" method="post" class="form-group row">
				<div class="col-md-4">
					<div class="form-group">
						<label>Nombre Compañia</label>
						<input class="form-control" type="text" name="div">
					</div>
				</div>
				<!-- codigo datatable -->
				<div class="col-lg-8 table-responsive" id="listcompanies">
                    <table id="tbcompanies" class="table table-striped table-bordered table-condensed">
                      <thead>
                        <th>ID</th>
                        <th>Company</th>
                        <th>Alias</th>
                      </thead>
                      <tbody>                            
                      </tbody>
                    </table>
                </div>
                <!-- fin datatable -->
			</form>
		</div>
	  </div> <!-- /tab-pane companies -->
	</div> <!-- fin #myTabContent -->
 	</div>	
 </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		//init
		listar('users');
		listar('accounts');
		listar('sections');
		listar('permisos');

		$("#frm_register").hide();

		$("#frm_register").submit(function(e) {
	      e.preventDefault();
	      register();
	    });
	    $("#frm_activedates").submit(function(e) {
	      e.preventDefault();
	      activedate();
	    });
	    $('#tbusers').on('click','tr',function () {
	    	$('.custom-control-input').prop("checked", false);

		    if ( $(this).hasClass('table-active') ) {
	            $(this).removeClass('table-active');
	            $('#frm_register input').val('');
	        }
	        else {
	            $('tr.table-active').removeClass('table-active');
	            $(this).addClass('table-active');
	            //pasar valores
				$('input[name=user]').val($(this).children().eq(0).text());
				$('input[name=nombre]').val($(this).children().eq(1).text());
				//mostrar permisos de divisiones 
				var sec = $(this).children().eq(2).text().match(/.{1,3}/g);
				sec.forEach(function(element){
					$('#'+element.toLowerCase()).prop("checked", true);
				});
			}
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

	function activedate(){ //metodo actualizar cierre
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

	function custombox(msg){ //mensaje personalizado
		bootbox.alert({
		    message: msg,
		    buttons: {
		        ok: {
		            className: 'btn-success'
		        }
		    }
		})
	}

	function showpass() { //muestra la contraseña
	  var x = document.getElementById("pwd");
	  if (x.type === "password") {
	    x.type = "text";
	  } else {
	    x.type = "password";
	  }
	}

	function listar(dir) { //muestra informacion en tablas
	var dir = dir.toString();
	tabla=$('#tb'+dir).dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: './ajax/'+dir+'.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "asc" ]]//Ordenar (columna,orden)
	}).DataTable();
}

function showfrm(flag)
{
	if (flag)
	{
		$("#frm_register").show();
		$("#listuser").hide();
		$("#btn_users").prop("disabled",false);
		$("#btn_users").hide();
	}
	else
	{
		$("#frm_register").hide();
		$("#listuser").show();
		$("#btn_users").show();
		//$("#btnagregar").show();
	}
}

</script>
    <!-- DATATABLES -->
    <script src="./datatables/jquery.dataTables.min.js"></script>    
    <script src="./datatables/dataTables.buttons.min.js"></script>
    <script src="./datatables/buttons.html5.min.js"></script>
    <script src="./datatables/buttons.colVis.min.js"></script>
    <script src="./datatables/jszip.min.js"></script>
    <script src="./datatables/pdfmake.min.js"></script>
    <script src="./datatables/vfs_fonts.js"></script> 

<?php 
include_once "views/footer.php";
 ?>