<?php 
require "../config/conn.php";
$section = sqlclean($_POST['section']);

//consulta de division x empresa
#query para traer las compañias 
//$arrcom = squery("SELECT idcompany, REPLACE(company, 'PROVECHO LP', 'REAL ESTATE') company, divisor FROM company WHERE idcompany IN (SELECT distinct(subcompany) FROM company where section = '$section')");

$arrcom = squery("SELECT idcompany, company, divisor FROM company WHERE idsection = '$section'");
$alenght = count($arrcom);

 ?>

<?php for ($i=0; $i < $alenght; $i++): 
	$idcompany = $arrcom[$i]['idcompany'];
	$name = $arrcom[$i]['company'];
	$divisor = $arrcom[$i]['divisor'];
?>


		<div class="col-md-12 my-2 p-2 divBorder" align="center" style="border: 2px solid green;border-radius: 6px; margin: auto;  overflow-x: auto;">
			<table class="table">
				<thead>
					<tr>
						<th class="text-center" colspan="13">PROJECTION <?php echo $name." ".date('Y'); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="13">Capture Date (Month-Day) From The Current Month: <?php echo capturedate($idcompany); ?></td>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td> - </td><td>JAN</td><td>FEB</td><td>MAR</td><td>APR</td><td>MAY</td><td>JUN</td><td>JUL</td><td>AUG</td><td>SEP</td><td>OCT</td><td>NOV</td><td>DEC</td>
					</tr>
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
	<?php endfor; 
	unset($arrcom);
	?>

<?php 
	function showpy($idcompany, $divisor, $row){

		/*--- metodo para ordenar registros mes x mes ---*/

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
		return $d['cdate'] ?? null;
	}

 ?>