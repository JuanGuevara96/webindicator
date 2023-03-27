<?php session_start();
require "views/header2.php";
require "config/conn.php";

?>
<!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<script src="js/all.js"></script>
<script src="js/jquery-3.4.0.min.js"></script> -->

<section>
<!-- capture -->
  <div  class="container">
  	<div class="row" style="border: 2px solid green;">
  		
  	<div class="col-md-4 d-flex justify-content-center p-2">	
  		<div class="flex-md-column">
			<h2>Capture settings ER</h2>
  		
			<div><!--inicio fecha mes -->
			  <form id="c_month">
			  	<div class="input-group input-group-sm">
				  	<div class="input-group-prepend">
	    				<span class="input-group-text bg-success"><i class="fas fa-calendar fa-inverse"></i></span>
	  				</div>
				    <select  id="date_r" name="month" class="custom-select text-center w-50"> 
				    	<?php for ($i=1; $i <= 12; $i++) { //todos los meses
				    	echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
				    	if (date('m') == $i) echo "selected='1'";
				    	echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
				    </select>
				    <select name="year" class="custom-select"> 
				      <option> <?php echo date('Y', strtotime('-1 year'));?></option>
				      <option selected="1"><?php echo date('Y');?></option>
				      <option><?php echo date('Y', strtotime('+1 year'));?></option>
				    </select>
				</div>
			  </form>
			</div> <!-- fin form checha -->

			<form id="ERdata" method="post">
			<fieldset class="form-group">
			    <div class="row">
			      <div class="col-sm-10">
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="rdtype" value="mes" checked>
			          <label class="form-check-label">
			            Mes / Month 
			          </label>
			        </div>
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="rdtype" value="division">
			          <label class="form-check-label">
			            Division
			          </label>
			        </div>
			        <div class="form-check">
			          <input class="form-check-input" type="radio" name="rdtype" value="pymes" id="rdpy">
			          <label class="form-check-label">
			            Projection
			          </label>
			        </div>
			      </div>
			    </div>
			</fieldset>
			  <div class="form-group">
			  <label>Company/Empresa</label>
			  <select name="company" class="form-control text-left">
			  	<!-- metodo colER() Jquery -->
	          </select>
			  </div>
			  <div class="form-group">
     			<label>Description/Concepto</label>
     			<select name="indexren" class="form-control text-left">
	            <?php  
	            $renglones = squery("select renc_descripcion as renglon, indexren from cfg_reports_ren where info_r = 'mes' order by indexren");
	            for ($i=0; $i < count($renglones); $i++) { 
	             echo "<option value='".$renglones[$i]['indexren']."'> ".$renglones[$i]['renglon']." </option>";
	            } unset($renglones);  ?>   
	        	</select>
			  </div>
			  <div class="input-group input-group-md">
				  <div class="input-group-prepend">
				    <span class="input-group-text" id="inputmoneda">$ </span>
				  </div>
				  <input type="text" class="form-control text-right" aria-label="Small" aria-describedby="inputGroup-sizing-sm" placeholder="$0" name="valnum">
   			  </div> 			  
		      	<button id="btn_saveER" type="submit" class="btn btn-success my-3 btn-block">Save <i class="fas fa-save"></i></button>
		        <div id="msg-capture" class="msg"></div>
			</form>

  		</div>
  	</div>
  	<div class="col-md-4 d-flex justify-content-center p-2">
  		<div class="flex-md-column w-100">
  		<h2>Data Captured</h2>
  		<div style="overflow-y: auto; height: 30em;">
	    <table class="table table-bordered">
	      <thead class="thead-light">
	        <tr>
	        	<th>Concepto</th>
	        	<th>Mes</th>
	        	<th>Acumulado</th>
	        	<th>Total</th>
	        </tr>
	      </thead>
	      <tbody id="tbERdata">
	        <!-- tabla metodo js tablaERdata()-->
	      </tbody>
	    </table>
  		</div>
		    <div id="spinner" class="text-center">
			<!-- insertado por js -->
			</div>
		</div>
  	</div>
  	<div class="col-md-4 d-flex justify-content-center p-2">
  		<div class="flex-md-row w-100">
  		<h2>INFOFIN REPORT
  		<button id="btn_ER600" type="submit" class="btn btn-success" style="vertical-align: top;">Execute <i class="fas fa-bolt"></i></button></h2>
  			<div id="ERxCompany" style="overflow-y: auto; height: 30em;">	
  			<div id="spinner2" class="text-center">
			<!-- insertado por js -->
			</div>
  			<!-- tabla metodo js ERxC -->
  			</div>
  		</div>
  	</div>
  	
  	</div> <!-- end row container -->
  </div>
</section>


<script type="text/javascript">
var monthName = [ "January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December" ];

	$(document).ready(function(){
		colER(); //muestra compañias x tipo
		renER(); //muestra conceptos x tipo 
		tableERdata();//muestra tabla de datos
		nextMonth();//muestra siguiente mes
	    
	    $("input[name=rdtype]").on('change', function() {
	      colER();
	      renER();
	      tableERdata();
	      $("#ERxCompany").html("");
	      if ($(this).val() == "mes")
	      	$("#btn_ER600").prop( "disabled", false);
	      else 
	      	$("#btn_ER600").prop( "disabled", true );
	    });
		$("#c_month, #ERdata select[name=company]").on('change', function(){
		   tableERdata();
		   nextMonth();
		   $("#ERxCompany").html("");
		 });
	   $("#ERdata").submit(function(e) {
	      e.preventDefault();
	      insertER();//inserta datos en la BD
	    });
	   $("#btn_ER600").click(function(e) {
	      ERxC();//inserta datos en la BD
	    });

	});

function colER(){
    var rdtype = $('#ERdata input[name=rdtype]:checked').val();
    $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {type: rdtype, op: "companies", section: ""},
      async: false,
      success: function(tb) {
        $("#ERdata select[name=company]").html(tb);
      }           
    });
}

function renER(){
    var rdtype = $('#ERdata input[name=rdtype]:checked').val();
    $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {type: rdtype, op: "renglones", section: ""},
      async: false,
      success: function(tb) {
        $("#ERdata select[name=indexren]").html(tb);
      }           
    });
}

function nextMonth(){
  var year = $("[name='year']").val();
  var month = $("#c_month select[name='month']").val();
  var datenow = new Date(year+"-"+month);
  datenow.setMonth(datenow.getMonth() + 2, 1);
  $("#rdpy").next().text("Projection " + monthName[new Date(datenow).getMonth()] + " " + year);
}

function tableERdata() {
  var company = $("#ERdata select[name=company]").val();
  if (company >= 800 && company < 900)
      $("#ERdata #inputmoneda").text("DLL $");
  else
      $("#ERdata #inputmoneda").text("MXN $");
  var nMonth = $("#c_month select[name='month']").val();
  var nYear = $("#c_month select[name='year']").val();
  var type = $("#ERdata input[name=rdtype]:checked").val();
      $.ajax( {
      url: "op/op.php",
      method: "GET",
      global: false,
      data: {idcompany: company, month: nMonth, year: nYear, op: "queryER", type_c: type, section: ""},
      //async: false,
      beforeSend: function() {
        // setting a timeout
       $("#tbERdata").html("");
       $("#spinner").append('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');//crea spinner de carga.
      },
      success: function(tb) {
        $("#tbERdata").html(tb);
      },
      complete: function(){
      	 $("#spinner div").remove(".spinner-grow");//elimina spinner
      }
    });
}

//input numbers format comma with decimals
  $("input[name=valnum]").keyup(function(event) {
      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40 || event.which == 173 || event.which == 190) return;
      //format number, ejem -5,000.0001
      $(this).val(function(index, value) {
        var parts = value.toString().replace(/(?!-)[^0-9.]/g, "").split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
      });
  });


function insertER(){
  $.ajax({
      url: "op/er.php",
      method: "post",
      global: false,
      data: $("#ERdata, #c_month").serialize() + "&section= " + "&er=settgs",
      dataType: "text",
      success: function(strmsg) {
        // setTimeout(function(){// wait for n secs(2)
        //       location.reload(); // then reload the page.(3)
        //   }, 10);
          if (strmsg != "")  //insert msg (3 secs)
            $('#msg-capture').append('<span class="fade-msg text-danger"> Error! Captured Fails <i class="fas fa-times"></i><p>'+strmsg+'</p></span>');
          else
           $('#msg-capture').append('<span class="fade-msg text-success"> Captured Success! <i class="fas fa-check"></i></span>');
          setTimeout(function(){// wait for n secs(2)
               $('.fade-msg').fadeOut();// then reload the page.(3)
          }, 3000);
          tableERdata();
      }
  });
}

function ERxC() {
	var company = $("#ERdata select[name=company]").val();
	var nMonth = $("#c_month select[name='month']").val();
  	var nYear = $("#c_month select[name='year']").val();
      $.ajax( {
      url: "op/er.php",
      method: "post",
      global: false,
      data: {er: "ERxCompany", idcompany: company, month: nMonth, year: nYear},
      //async: false,
      beforeSend: function() {
        // setting a timeout
       $("#ERxCompany").html("");
       $("#spinner2").append('<div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>');//crea spinner de carga.
      },
      success: function(tb) {
        $("#ERxCompany").html(tb);
      },
      complete: function(){
      	 $("#spinner2 div").remove(".spinner-grow");//elimina spinner
      }
    });
}


</script>
<?php
//include_once "views/footer.view.php";
 ?>