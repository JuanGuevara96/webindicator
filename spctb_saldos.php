<?php 
require './config/conn.php';
		#codigo nuevo reemplazar
	//$pvls = str_split($_SESSION['sections'], 3); #check priviliges
	//foreach ($pvls as $value) {
//$value = "ALI";
$date = "201909";
$netsales = 0;
$sectiones = array("ALI","AUT","COR","ENE","INM","USA");
echo "select * from test where section in (".implode(',',$sectiones).")";
		foreach ($sectiones as $value) {
			$arrcom = squery("select idcompany, subcompany, company, section, divisor from company where section like '".$value."%'");

		for ($i=0; $i < count($arrcom); $i++) { 
			#imprime tabla global
			extract($arrcom[$i], EXTR_OVERWRITE); #array associative name las convierte en variables 
			#arrays  
			$sum = ['netsales' => 0,'netgv'=>0, 'netga' =>0];
			$sumpm =['pmsales'=>0, 'pmgv'=>0, 'pmga'=>0];
			#ciclo de cuentas
			$acc = squery("SELECT A.idaccount, A.type, A.op, A.presup, P.numpre FROM 
			accounts A LEFT JOIN presup P ON A.idcompany=P.idcompany 
			AND P.ejercicio = '".substr($date,0,4)."' where A.idcompany = '".$idcompany."' AND A.type = 'I' ORDER BY A.idcompany, A.type");
			for ($j=0; $j < count($acc); $j++) {
				#imprime tablas por compañia
				extract($acc[$j], EXTR_OVERWRITE);
				$spctb = ocifunction($idcompany,$date, $idaccount, $numpre);
				$netsales = sum($op, $netsales, $spctb[':movnetos']);
				//$netsales += $spctb[':movnetos'];
				//print_r($spctb); echo "<br><br>";
			} #end for acc
		} #end for arrcom
		$netsales = number_format($netsales/1000);
		echo "<br>".$value." ";var_dump($netsales);
		} #foreach sectiones
	//} #end foreach pvls



		//$where .= " AND idcompany = '$idcompany'";

 ?>
