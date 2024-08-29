<?php session_start();
require "views/header.php";
require "./config/conn.php";
//require_once "config/lifetime.php";
#$tiempo_inicio = microtime_float();

#query select permisos y muestra divisiones 
$pvls = squery("Select s.idsection, UPPER(s.section_name) section_name, s.moneda, s.status from permisos p left join sections s ON p.idsection = s.idsection where s.status = '1' and p.iduser  = ".$_SESSION['ID']);

$listSec = null;
foreach ($pvls as $key => $value) {
	$listSec .= "<option value='".$value['idsection']."'>".$value['section_name']."</option>";
}

 ?>

<!-- <link rel="stylesheet" type="text/css" href="css/styles_proy.css"> -->
<!-- <div class="f-box" id="dateshow"><span class="fixed"><?php echo dateshow(date('Ym')); ?></span></div> -->
<style type="text/css">
	.hide {
	display: none;
}
</style>

<div class="container">

	<div align="center">
		<div id="list_divs" class="m-2 p-2 input-group w-75">

			<div class="input-group-prepend">
				<span class="input-group-text bg-success text-white">LIST SECTIONS</span>
			</div>
			<select name="division" class="custom-select text-center">
				<?php echo $listSec; ?>
			</select>

		</div>
	</div>

<!-- section data -->
<div class="row">
	
	
</div>


	<section class="my-4 p-2 w-75" style="border: 2px solid green;border-radius: 6px; margin: auto;">
		<span id="span_section"><h2 align="center">PROJECTION </h2></span>
		<div class="row" align="center">
			<form id="py-data" method="post" class="m-2 w-100">

		<!-- radio buttons -->
				<div class="rdbtn m-2">
					<fieldset class="form-group">
					    <div class="row">
					      <!-- <div class="col-sm-12 d-flex justify-content-sm-around"> -->
					        <div class="col-sm form-check">
					          <input class="form-check-input" id="rdaut" type="radio" name="rdaut" value="aut" checked="true">
					          <label class="form-check-label">
					            Automatic 
					          </label>
					        </div>
					        <div class="col-sm form-check">
					       	  <input class="form-check-input" id="rdstd" type="radio" name="rdaut" value="std">
					          <label class="form-check-label">
					            Manual
					          </label>
					        </div>
					      <!-- </div> -->
					    </div>
					</fieldset>
				</div>

				<div class="m-2 p-2 form-group" id="listxC">
			  		<label>Company/Empresa</label>
			  		<select name="company" class="form-control text-center">
			  			<!-- insert x Ajax, method lisxC -->
						
					</select>
				</div>

			  	<div class="m-2 p-2 input-group input-group-sm w-75">
				  	<div class="input-group-prepend">
	    				<span class="input-group-text bg-success"><i class="fas fa-calendar fa-inverse"></i></span>
	  				</div>
					<select name="pymonth" class="custom-select">
						<option value="<?php echo date('m');?>"><?php echo date('F'); ?></option>
						<?php for ($i=1; $i <= 12; $i++) { 
							echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."'> ".date('F',strtotime('01.'.$i.'.2001'))." </option>";
						} ?>
					</select>
					<input class="form-control" type="number" name="pyyear" min="2000" max="2099" value="<?php echo date('Y');?>" 
					pattern="[0-9]{4}">
	  			</div>


				<!-- insert manual -->
				<div id="py-std" class="hide">
			        <div class="m-2 w-50 input-group input-group-sm">
			           <div class="w-50 input-group-prepend">
			             <span class="w-100 input-group-text">Net Sales</span>
			           </div>
			           <input class="form-control text-right" type="text" name="netsales" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0">
			        </div>
			        <div class="m-2 w-50 input-group input-group-sm">
			           <div class="w-50 input-group-prepend">
			             <span class="w-100 input-group-text">Operative Expenses</span>
			           </div>
			          	<input class="form-control text-right" type="text" name="opexp" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0">
			        </div>
			        <div class="m-2 w-50 input-group input-group-sm">
			           <div class="w-50 input-group-prepend">
			             <span class="w-100 input-group-text">G&A Expenses</span>
			           </div>
			           <input class="form-control text-right" type="text" name="gaexp" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0">
			        </div>

				</div>

				<div class="mt-4">
					<button id="btn_py" type="submit" class="btn btn-success" >Save 
						<i class="fas fa-save"></i>
					</button>
				</div>

			</form>
		</div>
	<!-- ./ section data -->
	</section>


<!-- section tables -->
<section id="tbproy" class="container">
	<div id="spinner" class="text-center">
			<!-- insertado por js -->
	</div>
	<div id="py-tables" class="row">
		<!-- insetado x ajax, metodo tbProy -->
	</div>

	<!-- ./ section tables -->
</section>

	<!-- ./container -->
</div>

<script type="text/javascript">
	$(document).ready(function(){
		ProyName();
		listxC();
		tbProy();
		$("#list_divs select[name=division]").on('change', function(){
		   	$("#py-tables").html("");
		   	ProyName();
		   	listxC();
			tbProy();
		 });

	});

var	division;

	function ProyName(){
		var NameDiv = $("#list_divs select[name=division] option:selected").text();
		$('#span_section h2').text("PROJECTION "+ NameDiv);
	}

	function listxC(){
		division = $("#list_divs select[name=division]").val();
		$.ajax( {
	      url: "op/py.php",
	      method: "POST",
	      global: false,
	      data: {py: 'proyname', section: division},
	      async: false,
	      success: function(tb) {
	        $("#listxC select[name=company]").html(tb);
	      }           
	    });
	}

	function tbProy(){
		division = $("#list_divs select[name=division]").val();
		$.ajax( {
	      url: "modules/proy.php",
	      method: "POST",
	      global: false,
	      data: {section: division},
	      async: false,
	      beforeSend: function() {
	        // setting a timeout
	       $("#py-tables").html("");
	       $("#spinner").append('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');//crea spinner de carga.
	      },
	      success: function(tb) {
	        $("#py-tables").html(tb);
	      },
	      complete: function(){
	      	 $("#spinner div").remove(".spinner-grow");//elimina spinner
	      }           
	    });
	}


</script>

 <script src="js/py.js"></script>
<?php 
include "views/footer.php";
//$tiempo_fin = microtime_float();
//echo "<div><br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),2) . " seg.</div>";
 ?>