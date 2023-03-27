<?php 
require 'config/conn.php';
include 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$section = "ENERGETICOS";
$dateshow = "201908";//$_GET['dateshow'];
$tiempo_inicio = microtime_float();
			$reports = squery("SELECT idcompany, report, month FROM reports WHERE section = '$section'");
			//clean jpg files
			//deletefiles($section);
			//spreadsheet start
			$spreadsheet = new spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$spreadsheet->getProperties()->setCreator("Webindicator");
			$sheet->setTitle('Inicio');
			$sheet->setCellValue('A2', "Mes Cierre");
			$sheet->setCellValue('B2', date('m/Y', strtotime($dateshow)));
			$spreadsheet->addNamedRange(new NamedRange('MES_CIERRE', $spreadsheet->getSheet(0), 'B2'));

				foreach ($reports as $report) {
					$date = date('Ym', strtotime($dateshow));
					if ($report['month'] != "0") {
						$date = date('Y',strtotime($dateshow)).$report['month'];
					}
					#oci_exec(sql_calcula($report['idcompany'],$report['report'],$date));
					data($spreadsheet, $report['idcompany'], $report['report'],$section);
				}

$tiempo_fin = microtime_float();
echo "<br>Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
//functions
function data($spreadsheet, $idcompany,$report,$section) {
	$oci = oci_connect("INFOFIN", "INFOFIN", "192.168.100.90/ORCL");
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	//sentencia sql reportes
	$r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '$idcompany' and infi_reporte = '$report' order by infs_columna");
	$sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';
	for ($i=0; $i < count($r_col); $i++)
		$sql.=',renf_valor'.$r_col[$i]['COLUMNA'].' "'.$r_col[$i]['TITULO'].' '.$r_col[$i]['SUBTITULO'].'"'; 
	$sql .=" ,TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";

$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af');
//change it
//$spreadsheet = new Spreadsheet();
$spreadsheet->createSheet();
$nSheets = $spreadsheet->getSheetCount();
$sheet = $spreadsheet->getSheet($nSheets-1);
$sheet->setTitle($idcompany.'-'.$report);
//$sheet = $spreadsheet->setActiveSheetIndex(1);

	$stmt = oci_parse($oci,$sql);
	oci_execute($stmt);
	$x=2;

	$ncol = oci_num_fields($stmt);
	while ($row = oci_fetch_array($stmt, OCI_NUM+OCI_RETURN_NULLS)) {
		if ($x==2) {
			for ($i=0; $i < $ncol; $i++) { 
				$name = oci_field_name($stmt, $i+1);
				$sheet->setCellValue($letters[$i].'1', $name);
			}
		}
			for ($i=0; $i < $ncol; $i++) { 
				$sheet->setCellValue($letters[$i].$x, $row[$i]);
			}
		    $x++;
	} 
	$spreadsheet->addNamedRange(new NamedRange('rng_'.$idcompany.'_'.$report, $spreadsheet->getSheet($nSheets-1), 'B2:'.strtoupper($letters[$ncol-2]).$x));
	$writer = new Xlsx($spreadsheet); //other option to writer xlsx 
	//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	//$writer->setIncludeCharts(true);
	$writer->save('files\\'.$section.'\\data2.xlsx');
}
 ?>