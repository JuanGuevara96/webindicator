
<style type="text/css">
#datebox{
	position: fixed;
	z-index: 1030;
}
</style>

<!-- DateBox  -->
<nav class="navbar nav-datebox navbar-default m-2 pb-4">
	<div class="container">
		<form id="datebox" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
			<div class="input-group">
				<div class="input-group-prepend">
		    		<span class="input-group-text bg-success text-white">
		    			<i class="fas fa-calendar"></i>
		    		</span>
		  		</div>
				<select class="custom-select w-50" name="month">
					<?php for ($i=1; $i <= 12; $i++) { //todos los meses
			        echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."' ";
			        if (isset($_POST['month'])) {if ($_POST['month'] == $i) echo "selected='1'";} elseif (date('m') == $i) echo "selected='1'";
			        echo "> ".date('F',strtotime('01.'.$i.'.2001'))."</option>"; }?>
		        </select>
				<input class="custom-select w-25" type="number" name="year" min="2000" max="2099" value="<?php if(isset($_POST['year'])) echo $_POST['year']; else echo date('Y'); ?>" pattern="\d{4}" maxlength="4" minlength="4" style="background: #fff">
				<button class="btn btn-success button" type="submit">
					<i class="icon fas fa-search"></i>
				</button>
			</div>
		</form>
	</div>
</nav>