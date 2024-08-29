<?php 
session_start();
require_once "views/header.php";
require "config/conn.php";
$tiempo_inicio = microtime_float();



$companies = squery("SELECT idcompany FROM dbwebindicator.company WHERE idsection = 6");
$count = count($companies);


$oci = new ociDB();
$oci->connect();

// foreach ($companies as $item) {
// 	echo $oci->execute(sql_calcula($item['idcompany'],'600','202105'));
// }
$companies = MultiArrtoList($companies, 'idcompany');

$sql = "Select 
A.ctbs_cia, A.infs_renglon,  NVL(A.renc_descripcion, '-') renc_descripcion,
renf_valor1, renf_valor2, b.repd_fechultcalc
FROM INF_RENGLONES A, INF_REPORTES B 
where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE 
AND A.ctbs_cia IN ( ".$companies." )
AND A.INFI_REPORTE='600' ORDER BY A.INFS_RENGLON, A.ctbs_cia";
$arr = $oci->getRows($sql);

$oci->close();

$arrChunk = array_chunk($arr,$count);
echo "<pre>";

// print_r($companies);
// print_r($arrChunk);

?>

<div class="container">
	<h2> MES </h2>
	<table class="table table-striped">
		<thead>
				<th></th>
				<th><?php echo $arrChunk[0][0][0]; ?></th>
				<th></th>
				<th><?php echo $arrChunk[0][1][0]; ?></th>
				<th></th>
				<th><?php echo $arrChunk[0][2][0]; ?></th>
				<th></th>
				<th><?php echo $arrChunk[0][3][0]; ?></th>
				<th></th>
				<!-- <th><?php echo $arrChunk[0][4][0]; ?></th> -->
				<!-- <th><?php echo $arrChunk[0][5][0]; ?></th> -->
				<!-- <th><?php echo $arrChunk[0][6][0]; ?></th> -->
				<th>DIVISION</th>
				<th></th>
		</thead>
		<tbody>
			<?php 
			$tbody = "";
			$sum = 0;
			$aux = 0;
			for ($i=0; $i < count($arrChunk); $i++) { 
					$tbody .= "<tr>";
					$tbody .= "<td>".$arrChunk[$i][0][2]."</td>";
				foreach ($arrChunk[$i] as $value) {
					$val = round($value[3] / 1000);
					$tbody .= "<td>".number_format($val)."</td>";
					$vtas = $arrChunk[2][$aux][3] / 1000;
					$tbody .= "<td>".porc($vtas, $val)."</td>";
					$sum += $val;
					$aux += 1;
				}
					$tbody .= "<td>".number_format($sum)."</td>";
					$tbody .= "</tr>";
					$sum = 0;
					$aux=0;
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
				<th><?php echo $arrChunk[0][0][0]; ?></th>
				<th><?php echo $arrChunk[0][1][0]; ?></th>
				<th><?php echo $arrChunk[0][2][0]; ?></th>
				<th><?php echo $arrChunk[0][3][0]; ?></th>
				<!-- <th><?php echo $arrChunk[0][4][0]; ?></th> -->
				<!-- <th><?php echo $arrChunk[0][5][0]; ?></th> -->
				<!-- <th><?php echo $arrChunk[0][6][0]; ?></th> -->
				<th>DIVISION</th>
		</thead>
		<tbody>
			<?php 
			$tbody = "";
			for ($i=0; $i < count($arrChunk); $i++) { 
					$tbody .= "<tr>";
					$tbody .= "<td>".$arrChunk[$i][0][2]."</td>";
				foreach ($arrChunk[$i] as $value) {
					$tbody .= "<td>".number_format(round($value[4] / 1000))."</td>";
					$sum += round($value[4] / 1000);
				}
					$tbody .= "<td>".number_format($sum)."</td>";
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