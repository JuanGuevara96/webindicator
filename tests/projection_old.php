<?php session_start();
require "views/header.view.php";
require "./config/conn.php";
//require_once "config/lifetime.php";
$tiempo_inicio = microtime_float();

#query select permisos y muestra divisiones 
$pvls = squery("Select s.idsection, UPPER(s.section_name) section_name, s.moneda, s.status from permisos p left join sections s ON p.idsection = s.idsection where s.status = '1' and p.iduser  = ".$_SESSION['ID']);
//$result = $pvls ->fetch_assoc();
print_r($pvls);
$section = $pvls[0]['section_name'];

//$section = (isset($_POST['section'])) ? htmlspecialchars($_POST['section']): "";
$arrcom = squery("SELECT idcompany, REPLACE(company, 'PROVECHO LP', 'REAL ESTATE') company, divisor FROM company WHERE idcompany IN (SELECT distinct(subcompany) FROM company where section = '$section')");
$alenght = count($arrcom);
 ?>

<link rel="stylesheet" type="text/css" href="css/styles_proy.css">
<!-- <div class="f-box" id="dateshow"><span class="fixed"><?php echo dateshow(date('Ym')); ?></span></div> -->
	<div class="f-center">
		<span id="span_section"><h2 id="<?php echo $section;?>">PROJECTION <?php echo $section;?></h2></span>
		<div class="divBorder" style="padding: 8px;">
			<form id="py-data" method="post">
				<label>Company</label><select name="company" style="width: 26em;text-align: center;">
					<option id="all_id" value="0">--- all companies ---</option>
					<?php for ($i=0; $i < $alenght; $i++) { 
						echo "<option value='".$arrcom[$i]['idcompany']."'> ".$arrcom[$i]['company']." </option>";
					} ?>
				</select><br>
				<!-- data -->
				<label>Month</label><select name="pymonth" style="width: 10em;text-align: left;">
					<option value="<?php echo date('m');?>"><?php echo date('F'); ?></option>
					<?php for ($i=1; $i <= 12; $i++) { 
						echo "<option value='".str_pad($i, 2, "0", STR_PAD_LEFT)."'> ".date('F',strtotime('01.'.$i.'.2001'))." </option>";
					} ?>
				</select>
				<label>Year</label><input type="number" name="pyyear" min="2000" max="2099" value="<?php echo date('Y');?>" pattern="[0-9]{4}" style="width: 4em;text-align: left;"><br>
				<div id="py-std" class="hide">
				<!-- insert manual -->
					<label>Net Sales</label><input type="text" name="netsales" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0"><br>
					<label>Operative Expenses</label><input type="text" name="opexp" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0"><br>
					<label>G&A Expenses</label><input type="text" name="gaexp" pattern="[0-9]+([\.,][0-9]+)?" placeholder="$0"><br>
				</div>
				<div class="rdbtn">
					<br>
					<span><input id="rdaut" type="radio" name="rdaut" value="aut" checked="true">Automatic </span>
					<br>
					<span><input id="rdstd" type="radio" name="rdaut" value="std">Manual </span>
					<br>
				</div>
				<br><button id="btn_py" type="submit" class="button" style="float: right;">Save <i class="fas fa-save"></i></button>
				<!-- data end -->
			</form>
		</div>
	</div>
	<!-- tablas x meses-->
	<div id="py-tables" class="f-center">
	<?php for ($i=0; $i < $alenght; $i++): 
		$idcompany = $arrcom[$i]['idcompany'];
		$name = $arrcom[$i]['company'];
		$divisor = $arrcom[$i]['divisor'];
	?>
	<br>
	<div class="divBorder">
		<table class="h-table">
			<thead>
				<tr>
					<th>PROJECTION <?php echo $name." ".date('Y'); ?></th>
				</tr>
			</thead>
		</table>
		<table class="b-table">
			<thead>
				<tr>
					<td> - </td><td>JAN</td><td>FEB</td><td>MAR</td><td>APR</td><td>MAY</td><td>JUN</td><td>JUL</td><td>AUG</td><td>SEP</td><td>OCT</td><td>NOV</td><td>DEC</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="13">Capture Date (Month-Day) From The Current Month: <?php echo capturedate($idcompany); ?></td>
				</tr>
			</tfoot>
			<tbody>
				<!-- ciclo  -->
				<tr>
					<td>Net Sales</td><?php echo showpy($idcompany, $divisor, "netsales"); ?>
				</tr>
				<tr>
					<td>Operative Expenses</td><?php echo showpy($idcompany, $divisor, "opexp"); ?>
				</tr>
				<tr>
					<td>G&A Expenses</td><?php echo showpy($idcompany, $divisor, "gaexp"); ?>
				</tr>
			</tbody>
		</table>
	</div>
<?php endfor; ?>
</div>
<?php
	function showpy($idcompany, $divisor, $row){
		$td = "";
		for ($i=1; $i <= 12; $i++) { 
			//0 => 10, 1 => 05
		$pydate = date('Y').str_pad($i, 2, "0", STR_PAD_LEFT);
		$amount = oquery("select $row from proyec where idcompany = '$idcompany' and pydate = '$pydate'");
		$td .= ($i == date('m')) ? "<td style='background-color: antiquewhite;'>":"<td>";
		#$td .= ($amount) ? number_format($amount[0][$row]/$divisor,0) : "0"; //division a miles
		$td .= ($amount) ? number_format($amount[$row],0) : "0";
		$td .= "</td>";
		}
		return $td;
	}
	function capturedate($idcompany){
		$pydate = date('Ym');
		$d = oquery("select date_format(c_date, '%b-%d') cdate from proyec where idcompany = '$idcompany' and pydate = '$pydate'");
		return $d['cdate'];
	}

 ?>
 <script src="js/py.js"></script>
<?php 
//$tiempo_fin = microtime_float();
//echo "<div><br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),2) . " seg.</div>";
include "views/footer.php";
 ?>