<?php 
if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	}

set_time_limit(300);
require_once './config/conn.php';
// require_once './config/functions.php';

?>

<link rel="stylesheet" type="text/css" href="css/styles_tab.css">

<?php 
include_once "views/datebox.view.php";

if (isset($_POST['date'])) { //optimizar isset
	$date = date('Ym',strtotime($_POST['date']));
	$dateshow = dateshow($_POST['date']);
	$_SESSION['date'] = $_POST['date'];
} 
else { 
	$date = date('Ym'); 
	$dateshow = date('F j, Y');
	$_SESSION['date'] = $date;
}
	#contar tiempo de carga
	$tiempo_inicio = microtime_float(); 

	
	#variables
	$section = ''; $sectionaux = '';
 	$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0]; 
 	$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0]; $lastmov = '';

	$sections = squery("call SelDivsxuser('".$_SESSION['ID']."')");
?>

<!----- NAVBAR SECTIONS ------>
<!-- <nav class="navbar nav-sections navbar-default">
  <div class="container d-flex justify-content-end">
	  <ul class="nav btn-group btn-group-sm">
	  <?php 
	//   foreach ($sections as $item) {
	//   		echo "<li class='btn btn-outline-primary nav-item bg-white'><a class='nav-link' href='#".$item['section']."'>".$item['section']."</a></li>";
	//   	}
	  ?> 
	  </ul>
  </div>
</nav> -->

<?php 

/* Inicio de ciclo por division*/
	
	for ($h=0; $h < count($sections); $h++) { 
	 	extract($sections[$h], EXTR_OVERWRITE);

	//query suma de proyeccion
	$pyglobal = oquery("call SelSumProyec(".$date.", ".$idsection.")");


echo "<section class='section my-4' id='$section'>
			<div class='container'>
				<h2 class='text-center'>".$section."</h2>
					<h5 class='text-right'>miles, ".$moneda."</h5>";


echo '<table class="table table-borderless table-sm">
			<thead>
				<th colspan="8" class="text-center">GLOBAL '.$section.'</th>	
			</thead>
			<tbody>
			<tr class="text-center">
				<td>-</td>
				<td>REAL</td>
				<td>BUDGET</td>
				<td>VARIATION REALvsBUDG</td>
				<td>VARIATION REALvsBUDG(%)</td>
				<td>PROJECTION</td>
				<td>VARIATION PROJvsBUDG</td>
				<td>VARIATION PROJvsBUDG(%)</td>
			</tr>
			<tr class="text-right">
				<td class="text-left">Net Sales </td>
				<td id="'.$section.'netsales"></td>
				<td style="color:blue;" id="'.$section.'pmsales"></td>
				<td id="'.$section.'varsales" ></td>
				<td id="'.$section.'porcentsales" ></td>
				<td class="py">'.number_format($pyglobal['netsales']).'</td>
				<td id="'.$section.'pyvarsales"></td>
				<td id="'.$section.'pyporcentsales"></td>
			</tr>
			<tr class="text-right">
				<td class="text-left">Operative Expenses </td>
				<td id="'.$section.'netgv"></td>
				<td style="color:blue;" id="'.$section.'pmgv"></td>
				<td id="'.$section.'vargv" ></td>
				<td id="'.$section.'porcentgv"></td>
				<td class="py">'.number_format($pyglobal['opexp']).'</td>
				<td id="'.$section.'pyvargv"></td>
				<td id="'.$section.'pyporcentgv"></td>
			</tr>
			<tr class="text-right">
				<td class="text-left">G&A Expenses </td>
				<td id="'.$section.'netga"></td>
				<td style="color:blue;" id="'.$section.'pmga"></td>
				<td id="'.$section.'varga" ></td>
				<td id="'.$section.'porcentga" ></td>
				<td class="py">'.number_format($pyglobal['gaexp']).'</td>
				<td id="'.$section.'pyvarga"></td>
				<td id="'.$section.'pyporcentga"></td>
			</tr>
			</tbody>
		</table>
	 </div>';

	/*ciclo cuentas por empresa*/
	$arrcom = squery("call SelCompaniesxSection('".$idsection."','".substr($date,0,-2)."')");

	$oci = new ociDB();
	$oci->connect();

	for ($i=0; $i < count($arrcom); $i++) {

		extract($arrcom[$i], EXTR_OVERWRITE); #array associative name las convierte en variables 
		$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
		$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0];
		$lastmov ="";
		$acc = squery("SELECT idaccount, type, op, presup FROM 
		accounts where idcompany = '".$idcompany."' ORDER BY idcompany, type");

		$list = MultiArrtoList($acc, 'idaccount');
		$sql = "SELECT s.ctac_cta, ABS(salm_cargo - salm_credito) movnetos, COALESCE(p.prem_saldo, 0) FROM ctb_saldos s LEFT JOIN ctb_presupuestos p ON p.ctbs_cia=s.ctbs_cia and p.ctac_cta=s.ctac_cta and p.prei_aammejer=s.sali_aammejer WHERE s.ctbs_cia = '$idcompany' AND s.sali_aammejer = '$date' AND s.ctac_cta IN (".$list.")";

		$rows = $oci->getRows($sql, OCI_NUM);

		$saldos = array("netsales" => 0, "netgv" => 0, "netga" => 0);
		$presup = array("pmsales" => 0, "pmgv" => 0, "pmga" => 0);
		foreach ($acc as $item) {
		    foreach ($rows as $row) {
		        if ($row[0] == $item['idaccount']) {
					if ($item['op'] == 1){ //operador de resta o suma de la cuenta
						$saldos['netsales'] += ($item['type'] == 'I') ? $row[1] : 0;
						$saldos['netga'] 	+= ($item['type'] == 'A') ? $row[1] : 0;
						$saldos['netgv'] 	+= ($item['type'] == 'V') ? $row[1] : 0;
						
						$presup['pmsales'] 	+= ($item['type'] == 'I') ? $row[2] : 0;
						$presup['pmga'] 	+= ($item['type'] == 'A') ? $row[2] : 0;
						$presup['pmgv'] 	+= ($item['type'] == 'V') ? $row[2] : 0;
					}
					else {
						$saldos['netsales'] -= ($item['type'] == 'I') ? $row[1] : 0;
						$saldos['netga'] 	-= ($item['type'] == 'A') ? $row[1] : 0;
						$saldos['netgv'] 	-= ($item['type'] == 'V') ? $row[1] : 0;
						
						$presup['pmsales'] 	-= ($item['type'] == 'I') ? $row[2] : 0;
						$presup['pmga'] 	-= ($item['type'] == 'A') ? $row[2] : 0;
						$presup['pmgv'] 	-= ($item['type'] == 'V') ? $row[2] : 0;

					}
		        }
		    }
		}
		
		#!!!!!!!!!!!!! remplazar por javascript la division de numeros
		$saldos['netsales'] = $saldos['netsales'] / $divisor; 
		$saldos['netgv'] = $saldos['netgv'] / $divisor;
		$saldos['netga'] = $saldos['netga'] / $divisor;
		$sumpm = array_map('miles', $presup);
 		$arrcompany = array_merge($saldos, array_map('presupday', $sumpm));
		$arrcompany  += ['lastmov' => $lastmov];

		/**************CALCULOS DE INDICADORES****************/

		/*calculos para variacion mes vs presupuesto*/		
		$varsales = $arrcompany['netsales'] - $arrcompany['pmsales']; 
		$vargv =  	$arrcompany['netgv'] 	- $arrcompany['pmgv'];
		$varga =   	$arrcompany['netga'] 	- $arrcompany['pmga'];

		/*calculos para porcentaje de variacion mes vs presupuesto */
		$porcentsales = ($sumpm['pmsales']) ? $varsales / $arrcompany['pmsales'] * 100 : 0;
		$porcentgv = 	($sumpm['pmgv']) 	? $vargv 	/ $arrcompany['pmgv'] 	 * 100 : 0;
		$porcentga = 	($sumpm['pmga']) 	? $varga 	/ $arrcompany['pmga'] 	 * 100 : 0;

		$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0]; 
		$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0]; 
		$lastmov = '';
		$pcount = pold_count($idcompany,$dateshow) ?? ' - ';
		$pcolor = ($pcount > 0) ? 'style="background-color:yellow;"' : ''; //color para polizas no afectadas
		$py = array('netsales'=>0, 'opexp'=> 0, 'gaexp'=> 0);
		//$py = oquery("SELECT netsales, opexp, gaexp FROM proyec WHERE idcompany = '$idcompany' AND pydate = '$date'") ?? $py ;

		/*calculos para variacion mes vs proyeccion*/
		$pyvarsales = $py['netsales'] - $arrcompany['pmsales'];
		$pyvargv = $py['opexp'] - $arrcompany['pmgv'];
		$pyvarga = $py['gaexp'] - $arrcompany['pmga'];

		/*calculos para porcentaje de variacion mes vs proyeccion */
		$pyporcentsales = ($arrcompany['pmsales']) ? round($pyvarsales) / $arrcompany['pmsales'] * 100 :0; 
		$pyporcentgv = ($arrcompany['pmgv']) ? round($pyvargv) / $arrcompany['pmgv'] * 100 :0;
		$pyporcentga = ($arrcompany['pmga']) ? round($pyvarga) / $arrcompany['pmga'] * 100 :0;
	
?>

	<div class="divBorder">
			<div>
				<h6 class="font-weight-bold text-center"><?php echo $company;?></h6>
			</div>			
			<table class="tbcompany b-table table table-borderless table-sm" id="<?php echo $idcompany;?>">
			<thead>
			<tr class="text-center">
				<td>-</td>
				<td>REAL</td>
				<td>BUDGET</td>
				<td>VARIATION REALvsBUDG</td>
				<td>VARIATION REALvsBUDG(%)</td>
				<td>PROJECTION</td>
				<td>VARIATION PROJvsBUDG</td>
				<td>VARIATION PROJvsBUDG(%)</td>
			</tr>
			</thead>
			<tfoot>
			<tr>
				<td class="lastNetMov" colspan="4">Last Sale Mov: <?php echo $arrcompany['lastmov'];?></td>
				<td colspan="4" class="text-right">
					<span class="pold_count" <?php echo $pcolor;?>>Not Affected: <?php echo $pcount;?> </span>
				<a class="btn btn-success btn-sm" href="popup.php/?company=<?php echo $idcompany;?>&name=<?php echo $company;?>&section=<?php echo $section;?>&date=<?php echo $dateshow;?>" target="_blank" onclick="window.open(this.href,this.target,\'width=950,height=400,top=200,left=200,toolbar=no,location=no,directories=no,status=no,menubar=no\');return false;" id="plus">
					<i class="icon fas fa-plus" ></i>
				</a></td>
			</tr>
			</tfoot>
			<tbody>
			<tr class="text-right">
				<td class="text-left">Net Sales </td>
				<td class="netsales"><?php echo number_format(round($arrcompany['netsales']));?></td>
				<td class="pmsales"><?php echo number_format($arrcompany['pmsales']); ?></td>
				<td class="varsales"><?php echo number_format($varsales); ?></td>
				<td class="porcentsales"><?php echo number_format($porcentsales); ?>%</td>
				<td class="py"><?php echo number_format($py['netsales']); ?></td>
				<td class="pyvarsales"><?php echo number_format(round($pyvarsales)); ?></td>
				<td class="pyporcentsales"><?php echo number_format(round($pyporcentsales)); ?>%</td>
			</tr>
			<tr class="text-right">
				<td class="text-left">Operative Expenses </td>
				<td class="netgv"><?php echo number_format(round($arrcompany['netgv'])); ?></td>
				<td class="pmgv"><?php echo number_format($arrcompany['pmgv']); ?></td>
				<td class="vargv"><?php echo number_format($vargv); ?></td>
				<td class="porcentgv"><?php echo number_format($porcentgv); ?>%</td>
				<td class="py"><?php echo number_format($py['opexp']); ?></td>
				<td class="pyvargv"><?php echo number_format(round($pyvargv)); ?></td>
				<td class="pyporcentgv"><?php echo number_format(round($pyporcentgv)); ?>%</td>
			</tr>
			<tr class="text-right">
				<td class="text-left">G&A Expenses </td>
				<td class="netga"><?php echo number_format(round($arrcompany['netga'])); ?></td>
				<td class="pmga"><?php echo number_format($arrcompany['pmga']); ?></td>
				<td class="varga"><?php echo number_format($varga); ?></td>
				<td class="porcentga"><?php echo number_format($porcentga); ?>%</td>
				<td class="py"><?php echo number_format($py['gaexp']); ?></td>
				<td class="pyvarga"><?php echo number_format(round($pyvarga)); ?></td>
				<td class="pyporcentga"><?php echo number_format(round($pyporcentga)); ?>%</td>
			</tr>
			</tbody>
			</table>
		</div>


<?php

		}  #end for arrcom

	/*cierre ciclo por divisiones*/
		echo "</div>
			</section>";
		$oci->close();
	} #end foreach pvls



$tiempo_fin = microtime_float();
echo "<br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),2) . " seg.";


/* custom functions */


function presupday($pmref){ //calcula el presupuesto al dia actual
	global $date;
	if (date('Ym') == $date) {
		$day = date('d');
		$month =  new \DateTime('now');
		$month_days = $month->format('t');	
		return $pmref / $month_days * $day;
	}
	else {
		return $pmref;
	}
}

 ?>
		<script src="js/tbindicators.js"></script>