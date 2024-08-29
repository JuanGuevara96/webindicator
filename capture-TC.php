<?php 
session_start();
require "views/header.php";
require "config/conn.php";

include_once "views/datebox.view.php";
 ?>

<div class="container">

     <div class="my-4 float-right">
     	<button id="btn_add" type="button" class="btn btn-success" data-toggle="modal" data-target="#currency_modal" title="add currency">
			<i class="fas fa-dollar-sign"></i>
     		add currency
		</button>
     </div>
	<div class="table table-responsive">
		<!-- <button type="submit">Submit form</button> -->
		<table id="tableTC" class="table table-striped table-bordered dt-responsive nowrap w-100">
			<thead>
				<th>CONCEPTO</th>
				<th>EJERCICIO</th>
				<th>ENE</th>
				<th>FEB</th>
				<th>MAR</th>
				<th>ABR</th>
				<th>MAY</th>
				<th>JUN</th>
				<th>JUL</th>
				<th>AGO</th>
				<th>SEP</th>
				<th>OCT</th>
				<th>NOV</th>
				<th>DIC</th>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

<!-- MODAL BUSQUEDA CLIENTE -->
<div class="modal fade" id="currency_modal" tabindex="-1" role="dialog" aria-labelledby="currency_ModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h3 class="modal-title font-weight-bold" id="currency_ModalLabel">ADD CURRENCY <span></span></h3>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <form id="currency_form">
	      	<div class="modal-body">
				<div class="container">

				<div class="d-flex flex-column align-items-center p-4">
					<div id="date_m"><span class="text-left font-weight-bold text-uppercase"></span></div>
					<select id="selectClv" class="custom-select my-2 w-75">
					  <option selected>Open this select menu</option>
					</select>
					<div class="input-group my-2 w-75">
					  <div class="input-group-prepend">
					    <span class="input-group-text">currency / divisa</span>
					  </div>
					  <input type="text" class="form-control text-right" aria-label="Small" aria-describedby="inputGroup-sizing-sm" name="moneda" placeholder="$0.00">
					</div>
				</div>	

		      </div>
		      <div class="modal-footer">
		        <button type="submit" class="btn btn-info" >Save
		        </button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>

	      	</form>
	    </div>
	  </div>
	</div>

	<!-- ./container -->
</div>


<script type="text/javascript">
$(document).ready(function() {
	//init
	tb();

    // //ERROR HANDLING DATATABLES
    $.fn.dataTable.ext.errMode = function ( settings, helpPage, message ) { 
        console.log(message);
    };

	$("#currency_form").submit(function(e) {
		e.preventDefault();
		var clv = $('#selectClv').val();
		$.post( "modules/capture-tc.php", 
			$("#currency_form").serialize() +"&"+ $("#datebox").serialize() + "&op=add&clave=" + clv
		).done(function( data ) {
		    $("#tableTC").DataTable().ajax.reload();
		    $("#currency_modal").modal('hide');
		});
	});

	$("#currency_modal").on('show.bs.modal', function (e) {
	  selectCLv();
	});
 
});

var tb = function (){
	var yyyy = $('#datebox [name=year]').val();
	var table = $('#tableTC').DataTable({
       "Processing": true,
       "dom": 'rt<"clear">', 
	    "ajax":{
                "method":"post",
                "url":"modules/capture-tc.php",
                "data": {op:"mostrar", year: yyyy} 
               },
	    "columns": [
	        { "data": "descrip" },
	        { "data": "AAAA" },
	        { "data": "column01" },
	        { "data": "column02" },
	        { "data": "column03" },
	        { "data": "column04" },
	        { "data": "column05" },
	        { "data": "column06" },
	        { "data": "column07" },
	        { "data": "column08" },
	        { "data": "column09" },
	        { "data": "column10" },
	        { "data": "column11" },
	        { "data": "column12" }
	    ],
	    "order": [[ 0, "asc" ]],
        "bDestroy": true
	});
	table.clear().draw();
}

function selectCLv(){
	var month = $('#datebox  option:selected' ).text();
	$("#date_m span").text(month);
	 $.ajax( {
        url: "modules/capture-tc.php",
        method: "post",
        data: {op: "selectCollection"},
        dataType: "html",
        success: function(data) {
        	$("#selectClv").html(data);
        }
    });
}

</script>

<?php 
require "views/footer.php";
 ?>