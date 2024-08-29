<?php session_start();
require "config/conn.php";
require_once "views/header.php";
include_once "views/datebox.view.php";

 ?>

<div class="container">
<!-- capture -->
<section class="p-4 mb-2">
	<form id="ERdata" method="post">
		<fieldset class="row">
			<div class="border border-success rounded shadow d-flex">
		  		<div class="col-4">
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
		  		<div class="col-4">
					  <div class="form-group">
					  <label>Company/Empresa</label>
					       <select name="company" class="form-control text-left">
						  <!-- metodo colER() Jquery -->
				          </select>
					  </div>
		  		</div>
		  		<div class="col-4 d-flex flex-wrap align-content-center">
					  <button type="button" id="btn_capture" class="btn btn-secondary">Mostrar captura
					  </button>			  
		  		</div>
			</div>
		</fieldset>
	</form>
</section>

<section class="p-4 mb-2">
	<fieldset class="row">
		
		<div class="col-md-6">
	  		<div class="flex-md-column w-100">
	  		<h2>Data Captured</h2>
	  		<div style="overflow-y: auto; height: 30em;">
		    <table class="table table-bordered">
		      <thead class="thead-light">
		        <tr>
		        	<th>CONCEPTO</th>
		        	<th>MES</th>
		        	<th>ACUM</th>
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
	  	<div class="col-md-6">
	  		<div class="flex-md-row w-100">
	  		<h2>INFOFIN REPORT
	  		<button id="btn_ER600" type="submit" class="btn btn-success" style="vertical-align: top;">Execute <i class="fas fa-bolt"></i></button></h2>
	  			<div >
		  			<table id="tbERxC" class='table table-bordered'>
						<thead class='thead-light'>
							<tr>
							<th>RENGLON</th>
							<th>CONCEPTO</th>
							<th>MES</th>
							<th>ACUM</th>
							</tr>
						</thead>
						<tbody>
							<!-- insert js ERxC	 -->
						</tbody>
					</table>
		  			<div id="spinner2" class="text-center">
					<!-- insertado por js -->
					</div>
	  			</div>
	  		</div>
	  	</div>
	</fieldset>
</section>


</div>

<script src="js/ERcapture.js"></script>