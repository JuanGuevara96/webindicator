<?php 
session_start();
require_once "views/header.php";
require "config/conn.php";
$tiempo_inicio = microtime_float();



$renc = squery("SELECT ren_name as renc_descripcion, inf_renglones from cfg_reports_ren WHERE info_r = 'ERCD'");
$inf_reng = MultiArrtoList($renc, 'inf_renglones');

$oci = new ociDB();
$oci->connect();


echo "<pre>";
$section = array('1', '4', '5', '6', '7');
foreach ($section as $idsection) {
	$companies = squery("SELECT idcompany FROM company WHERE idsection = '$idsection'");
	$companies = MultiArrtoList($companies, 'idcompany');
	$sql = "Select A.infs_renglon,
	SUM(renf_valor1) mes, SUM(renf_valor2) acumulado
	FROM INF_RENGLONES A, INF_REPORTES B 
	where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE 
	AND A.ctbs_cia IN (".$companies.")
	AND A.infs_renglon IN (".$inf_reng.")
	AND A.INFI_REPORTE='600' 
	GROUP BY A.infs_renglon
	ORDER BY A.infs_renglon";
	$arr[$idsection] = $oci->getRows($sql);
}
	// print_r($arr);

$oci->close();




// $count = count($companies);
// $arrChunk = array_chunk($arr,$count);
// print_r($companies);

?>

<div class="container">
	<h2> MES </h2>
	<table class="table table-striped">
		<thead>
				<th></th>
				<th>ALIMENTOS MX</th>
				<th></th>
				<th>ENERGETICOS</th>
				<th></th>
				<th>INMOBILIARIA</th>
				<th></th>
				<th>ALIMENTOS EU</th>
				<th></th>
				<th>REAL ESTATE</th>
				<th></th>
				<th>GRUPO</th>
		</thead>
		<tbody>
			<?php 
			$tbody = "";
			$sum = 0;
			for ($i=0; $i < count($renc); $i++) { 
					$tbody .= "<tr>";
					$tbody .= "<td>".$renc[$i]['renc_descripcion']."</td>";
				foreach ($section as $idsection) {
					$val = $arr[$idsection][$i][1] / 1000;
					$sum += $val;
					$tbody .= "<td>".number_format(round($val))."</td>";

					$vtas = $arr[$idsection][0][1] / 1000;
					$tbody .= "<td>".porc($vtas, $val)."</td>";
				}
					$tbody .= "<td>".number_format(round($sum))."</td>";
					$tbody .= "</tr>";
					$sum = 0;
			}
			echo $tbody;

			 ?>
		</tbody>
	</table>
</div>


<div class="container">
	<h2> ACUMULADO</h2>
	<table class="table table-striped">
		<thead>
				<th></th>
				<th>ALIMENTOS MX</th>
				<th></th>
				<th>ENERGETICOS</th>
				<th></th>
				<th>INMOBILIARIA</th>
				<th></th>
				<th>ALIMENTOS EU</th>
				<th></th>
				<th>REAL ESTATE</th>
				<th></th>
				<th>GRUPO</th>
		</thead>
		<tbody>
			<?php 
			$tbody = "";
			$sum = 0;
			for ($i=0; $i < count($renc); $i++) { 
					$tbody .= "<tr>";
					$tbody .= "<td>".$renc[$i]['renc_descripcion']."</td>";
				foreach ($section as $idsection) {
					$val = $arr[$idsection][$i][2] / 1000;
					$sum += $val;
					$tbody .= "<td>".number_format(round($val))."</td>";

					$vtas = $arr[$idsection][0][2] / 1000;
					$tbody .= "<td>".porc($vtas, $val)."</td>";
				}
					$tbody .= "<td>".number_format(round($sum))."</td>";
					$tbody .= "</tr>";
					$sum = 0;
			}
			echo $tbody;

			 ?>
		</tbody>
	</table>
</div>








<?php

function porc($cantidad,$valor){
	return ($valor > 0) ? "%".round(($valor/$cantidad)*100, 1) : "N/D";
}
// fin de tiempo de ejecucion
$tiempo_fin = microtime_float();
echo "<br>Loading Time : " . number_format(($tiempo_fin - $tiempo_inicio),2) . " seg.";

 ?>