<?php 


if (!isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	}

set_time_limit(300);
require_once './config/conn.php';
require './class/tbindicators.php';
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


	$sections = squery("call SelDivsxuser('".$_SESSION['ID']."')"); //ID DE USUARIO
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
	
for ($h=0; $h < count($sections); $h++) { //AQUI SE ITERAN LAS DIVISIONES
    extract($sections[$h], EXTR_OVERWRITE);

	//query suma de proyeccion
	$pyglobal = oquery("call SelSumProyec(".$date.", ".$idsection.")"); // NO UTILIZADO


    $obj = new tbIndicators($section);
	$obj->section = $section;
	$obj->dateshow = $dateshow;

    /*ciclo cuentas por empresa*/
    $arrcom = squery("call SelCompaniesxSection('".$idsection."','".substr($date,0,-2)."')"); // CICLO DE CUENTAS POR EMPRESA

    //CONEXION CON ORACLE
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
		$pyporcentgv    = ($arrcompany['pmgv']) ? round($pyvargv) / $arrcompany['pmgv'] * 100 :0;
		$pyporcentga    = ($arrcompany['pmga']) ? round($pyvarga) / $arrcompany['pmga'] * 100 :0;

        $obj->company   = $company;
        $obj->idcompany = $idcompany;
        $obj->lastmov   = $arrcompany['lastmov'];
        $obj->pcount    = $pcount;
        //number arrays
        $obj->budget      = $sumpm;
        $obj->real        = $saldos;
        $obj->var_RB      = array($varsales, $vargv, $varga);
        $obj->var_RB_per  = array($porcentsales, $porcentgv, $porcentga);
        $obj->projection  = array(0,0,0);
        $obj->var_PB      = array($pyvarsales, $pyvargv, $pyvarga);
        $obj->var_PB_per  = array($pyporcentsales, $pyporcentgv, $pyporcentga);
        $obj->Indicator();
    }  #end for arrcom
        
        #destructor debe termina aqui
        $obj->EndDiv();
		exit();

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