<?php 
require 'config/conn.php';
include 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$section = "alimentos mx";
$dateshow = "2019-09";//$_GET['dateshow'];
$x=2; $rng = 3;
$tiempo_inicio = microtime_float();
			#$reports = squery("SELECT idcompany, report, month FROM reports WHERE report = 600 AND section = '$section' ORDER BY section, idcompany");//section = '$section'");
						$reports = squery("SELECT idcompany, report, month FROM reports WHERE report = 600 AND section = '$section' ORDER BY section, idcompany");//section = '$section'");
			$renglones = array("50","60","65","70","80","90","100","110","120","130","135","140","150","155","160","170","180","190","200","210");
			//deletefiles($section); #clean jpg files		

			//spreadsheet start
			$spreadsheet = new spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$spreadsheet->getProperties()->setCreator("Webindicator");
			$sheet->setTitle('Inicio');
			$sheet->setCellValue('A2', "Mes Cierre");
			$sheet->setCellValue('B2', date('m/Y', strtotime($dateshow)));
			//$spreadsheet->addNamedRange(new NamedRange('MES_CIERRE', $spreadsheet->getSheet(0), 'B2'));
			$spreadsheet->createSheet();
				//foreach ($reports as $report) {
					// $date = date('Ym', strtotime($dateshow));
					// if ($report['month'] != "0") {
					// 	$date = date('Y',strtotime($dateshow)).$report['month'];
					// }
					//oci_exec(sql_calcula($report['idcompany'],'600',$date));
			foreach ($renglones as $renglon) {
					data($spreadsheet, $renglon, '600',$section);
			}
				//}

$tiempo_fin = microtime_float();
echo "<br>Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
//functions
function data($spreadsheet, $renglon ,$report,$section) {
	$oci = oci_connect("INFOFIN", "INFOFIN", "192.168.100.90/ORCL");
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	// sentencia sql reportes
	// $r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '$idcompany' and infi_reporte = '$report' order by infs_columna");
	//  $sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';

	// for ($i=0; $i < count($r_col); $i++)


	$sql = "Select Sum(renf_valor1) Mes, Sum(renf_valor2) Acum,  Sum(renf_valor3) AA,  Sum(renf_valor4) Ppto FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE  AND A.INFI_REPORTE= 8000 
AND infs_renglon = ".$renglon."
ORDER BY A.INFS_RENGLON";	


$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af');
//change it
//$spreadsheet = new Spreadsheet();
//$spreadsheet->createSheet();
$nSheets = $spreadsheet->getSheetCount();
$sheet = $spreadsheet->getSheet($nSheets-1);
$sheet->setTitle($report);//setTitle($idcompany.'-'.$report);
//$sheet = $spreadsheet->setActiveSheetIndex(1);

	$stmt = oci_parse($oci,$sql);
	oci_execute($stmt);
	global $x;
	$aux = $x;
	$ncol = oci_num_fields($stmt); 
	//nombres de columnas reporte
	// for ($j=0; $j < $ncol; $j++) { 
	// 	$name = oci_field_name($stmt, $j+1);
	// 	$sheet->setCellValue($letters[$j+1].'1', $name);
	// }

	while ($row = oci_fetch_array($stmt, OCI_NUM+OCI_RETURN_NULLS)) {
		for ($i=0; $i < $ncol; $i++) {
			$sheet->setCellValue($letters[$i+1].$x, $row[$i]);
		}
		$sheet->setCellValue($letters[0].$x, $renglon);
	    $x++;
	}
	// $spreadsheet->addNamedRange(new NamedRange('titulo_'.$idcompany.'_'.$report, $spreadsheet->getSheet($nSheets-1), 'A'.($aux).':'.strtoupper($letters[$ncol-1]).$aux ));
	// $spreadsheet->getSheet($nSheets-1)->getStyle('A'.$aux.':'.strtoupper($letters[$ncol-1]).$aux)->getFont()->getColor()->setARGB('FFFF0000');
	// $spreadsheet->addNamedRange(new NamedRange('rng_'.$idcompany.'_'.$report, $spreadsheet->getSheet($nSheets-1), 'B'.($aux+1).':'.strtoupper($letters[$ncol-2]).($x-1)));

	$writer = new Xlsx($spreadsheet); //other option to writer xlsx 
	//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	//$writer->setIncludeCharts(true);
	$writer->save('files/CORPORATIVO/data.xlsx');
}
 ?>