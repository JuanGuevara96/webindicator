<?php 
require '../config/conn.php';
if (isset($_POST['pyyear']) && isset($_POST['pymonth']) && isset($_POST['section'])){
	$year = htmlspecialchars($_POST['pyyear']);
	$dateFormat = $year."-".htmlspecialchars($_POST['pymonth']);
	$date = date('Ym',strtotime($dateFormat));
 	$section = htmlspecialchars(strtolower($_POST['section']));
 	$idcompany = htmlspecialchars($_POST['company']);
 	$cdate = date('Y-m-d'); //current date capture
	switch ($_POST['rdaut']) {
	case 'aut':
		$sql = "SELECT distinct(subcompany) FROM company where section = '$section'";
		if ($idcompany != 0){
			$sql .= " AND idcompany = '$idcompany'"; //continuar, verificar seleccion de 1 compañia o varias
		}
		$companies = squery($sql);
		foreach ($companies as $company) {
			$idcompany = $company['subcompany'];
			$sumcurrent = calcMonth($date,$idcompany);
			#echo "<br>".$idcompany;print_r($sumcurrent);echo " fecha: $date ";
			$datestring = $dateFormat.' first day of last month';
			$dt = date_create($datestring);
			$preMonth = $dt->format('Ym'); 
			$sumprev = calcMonth($preMonth,$idcompany);
			#echo "<br>".$idcompany;print_r($sumprev);echo " fecha: $preMonth ";
			$py = pyMonth($sumcurrent, $sumprev);
			$netsales = $py['netsales'];
			$opexp = $py['netgv'];
			$gaexp = $py['netga'];
			//echo "<br>".$idcompany;print_r($py);echo " <br>";
			//se inserta n veces, verificar tomar los n valores de status
		$status = equery("INSERT INTO proyec (idcompany, pydate, section, netsales, opexp, gaexp, c_date) VALUES ('$idcompany', '$date', '$section', '$netsales', '$opexp', '$gaexp', '$cdate') ON DUPLICATE KEY UPDATE netsales = '$netsales', opexp = '$opexp', gaexp = '$gaexp', c_date = '$cdate'");
			if (!$status) echo $status;
		}
		break;
	case 'std':
		$netsales = ($_POST['netsales']) ? htmlspecialchars(str_replace(",","",$_POST['netsales'])):0;
		$opexp = ($_POST['opexp']) ? htmlspecialchars(str_replace(",","",$_POST['opexp'])):0;
		$gaexp = ($_POST['gaexp']) ? htmlspecialchars(str_replace(",","",$_POST['gaexp'])):0;
		if ($idcompany == 0) 
			$status = "select a company";
		else
			$status = equery("INSERT INTO proyec (idcompany, pydate, section, netsales, opexp, gaexp, c_date) VALUES ('$idcompany', '$date', '$section', '$netsales', '$opexp', '$gaexp', '$cdate') ON DUPLICATE KEY UPDATE netsales = '$netsales', opexp = '$opexp', gaexp = '$gaexp', c_date = '$cdate'");
			if (!$status) echo $status;
		break;
	}
}

 function calcMonth($date, $idcompany){
 	global $section; global $year;
 	$arr = oquery("SELECT divisor FROM company WHERE idcompany = '$idcompany'");
 	$arrcom = squery("SELECT C.idcompany, A.idaccount, A.type, A.op, P.numpre, C.divisor FROM 
dbwebindicator.company C INNER JOIN dbwebindicator.accounts A ON C.idcompany=A.idcompany LEFT JOIN dbwebindicator.presup P ON 
C.idcompany=P.idcompany  AND P.ejercicio = '$year' WHERE C.section = '$section' AND C.subcompany = '$idcompany' ORDER BY type, C.idcompany");
	$length = count($arrcom);
	$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
	for ($i=0; $i < $length; $i++){
		$PM = ocifunction($arrcom[$i]['idcompany'],$date, $arrcom[$i]['idaccount'], $arrcom[$i]['numpre']);
			switch ($arrcom[$i]['type']) {
			case 'I':
				$sum['netsales'] = sum($arrcom[$i]['op'], $sum['netsales'], $PM[':movnetos']);
				break;
			case 'V':
				$sum['netgv'] = sum($arrcom[$i]['op'], $sum['netgv'], $PM[':movnetos']);
				break;
			case 'A':
				$sum['netga'] = sum($arrcom[$i]['op'], $sum['netga'], $PM[':movnetos']);
				break;	
		}
	}
	//$sum = array_map("", $sum);
	$sum['netsales'] = $sum['netsales'] / $arr['divisor'];
	$sum['netgv'] = $sum['netgv'] / $arr['divisor'];
	$sum['netga'] = $sum['netga'] / $arr['divisor'];
	return $sum;
 }

 function pyMonth($Mcurrent, $Mprev){
 	$py = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
 	if ($Mprev['netsales'] != 0) {
	 	$py['netsales'] = calcxday($Mcurrent['netsales']);
		$py['netgv'] = $py['netsales']*($Mprev['netgv'] / $Mprev['netsales']);
		$py['netga'] = $py['netsales']*($Mprev['netga'] / $Mprev['netsales']);
 	}
	return $py;
 }
function calcxday($ref){
	global $date;
	//format date Ym;
	if (date('Ym') == $date) {
		$day = date('d');
		$month =  new \DateTime('now');
		$month_days = $month->format('t');
		return $ref / $day * $month_days;
	}
	else {
		return $ref;
	}
}

 ?>