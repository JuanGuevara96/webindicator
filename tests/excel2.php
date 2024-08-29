<?php 
require 'config/conn.php';
include 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//$section = "alimentos mx";
$dateshow = "2019-09";//$_GET['dateshow'];
$x=1; $rng = 3;
$tiempo_inicio = microtime_float();
			//clean jpg files
			//deletefiles($section);
			//spreadsheet start
			//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('files\\data_compilation.xlsx');
			$reports = squery("SELECT idcompany, report, month, section FROM reports WHERE descrip = 'BDFE' ORDER BY section, idcompany");//section = '$section'");
			$spreadsheet = new spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$spreadsheet->getProperties()->setCreator("Webindicator");
			$sheet->setTitle('Inicio');
			$sheet->setCellValue('A2', "Mes Cierre");
			$sheet->setCellValue('B2', date('m/Y', strtotime($dateshow)));
			$spreadsheet->addNamedRange(new NamedRange('MES_CIERRE', $spreadsheet->getSheet(0), 'B2'));
			$spreadsheet->createSheet();
				foreach ($reports as $report) {
					$section = $report['section'];
					$date = date('Ym', strtotime($dateshow));
					if ($report['month'] != "0") {
						$date = date('Y',strtotime($dateshow)).$report['month'];
					}
					//oci_exec(sql_calcula($report['idcompany'],$report['report'],$date));
					data($spreadsheet, $report['idcompany'],$report['report'],$section);
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
	// sentencia sql reportes
	$r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '$idcompany' and infi_reporte = '$report' order by infs_columna");
	 $sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';

	for ($i=0; $i < count($r_col); $i++)

	$sql.=',renf_valor'.$r_col[$i]['COLUMNA'].' "'.$r_col[$i]['TITULO'].' '.$r_col[$i]['SUBTITULO'].'"';

	$sql .=" ,TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";


	// $sql = "Select infs_renglon renglon, RENC_DESCRIPCION CONCEPTO, renf_valor1 SI_ANO, renf_valor2 CaEnero,  renf_valor3 CrEnero,  renf_valor4 CaFebrero,  renf_valor5 CrFebrero,  renf_valor6 CaMarzo,  renf_valor7 CrMarzo, renf_valor8 SalMarzo,  renf_valor9 CaAbril,  renf_valor10 CrAbril,renf_valor11 CaMayo,  renf_valor12 CrMayo, renf_valor13 CaJunio, renf_valor14 CrJunio, renf_valor15 SalJunio,renf_valor16 CaJulio,renf_valor17 CrJulio,renf_valor18 CaAgosto,renf_valor19 CrAgosto, renf_valor20 CaSeptiebre, renf_valor21 CrSeptiembre,renf_valor22 SalSeptiembre,renf_valor23 CaOctubre,renf_valor24 CrOctubre,renf_valor25 CaNoviembre,renf_valor26 CrNoviembre,renf_valor27 CaDiciembre,renf_valor28 CrDiciembre, renf_valor29 SalDiciembre,   TO_CHAR(REPD_FECHAULTMOD,'MON-YYYY')  FECHA  FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany."AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";

		// $sql = "Select infs_renglon renglon, RENC_DESCRIPCION CONCEPTO, renf_valor1 , renf_valor2 ,  renf_valor3 ,  renf_valor4 ,  renf_valor5 ,  renf_valor6 ,  renf_valor7 , renf_valor8 ,  renf_valor9 ,  renf_valor10 , , renf_valor11 , renf_valor12 , renf_valor13 , renf_valor14 , renf_valor15 ,renf_valor16 ,renf_valor17 ,renf_valor18 ,renf_valor19 , renf_valor20 , renf_valor21 ,renf_valor22 ,renf_valor23 ,renf_valor24 ,renf_valor25 ,renf_valor26 ,renf_valor27 ,renf_valor28 , renf_valor29 ,   TO_CHAR(REPD_FECHAULTMOD,'MON-YYYY')  FECHA  FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany."AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";

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
	$ncol = oci_num_fields($stmt); $string = "";
	//nombres de columnas reporte
	for ($j=0; $j < $ncol; $j++) { 
		$name = oci_field_name($stmt, $j+1);
		$sheet->setCellValue($letters[$j].$x, $name);
	}
	$sheet->setCellValue($letters[0].$x, "$section '$idcompany'");
	$x++;
	while ($row = oci_fetch_array($stmt, OCI_NUM+OCI_RETURN_NULLS)) {
		for ($i=0; $i < $ncol; $i++) {
			$sheet->setCellValue($letters[$i].$x, $row[$i]);
		}
	    $x++;
	}
	$spreadsheet->addNamedRange(new NamedRange('titulo_'.$idcompany.'_'.$report, $spreadsheet->getSheet($nSheets-1), 'A'.($aux).':'.strtoupper($letters[$ncol-1]).$aux ));
	$spreadsheet->getSheet($nSheets-1)->getStyle('A'.$aux.':'.strtoupper($letters[$ncol-1]).$aux)->getFont()->getColor()->setARGB('FFFF0000');
	$spreadsheet->addNamedRange(new NamedRange('rng_'.$idcompany.'_'.$report, $spreadsheet->getSheet($nSheets-1), 'B'.($aux+1).':'.strtoupper($letters[$ncol-2]).($x-1)));

	//incio config
	global $rng;
	$sheet = $spreadsheet->getSheet(1);
	//$sheet->setCellValue('A1', 'rng_'.$idcompany.'_'.$report);
	//$sheet->setCellValue('B'.$rng, "=INDICE(INDIRECTO(A".$rng."), COINCIDIR(50,'600'!A:A,0)-1,2)");
	$rng++;
	$writer = new Xlsx($spreadsheet); //other option to writer xlsx 
	//$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
	//$writer->setIncludeCharts(true);
	$writer->save('files\\data_compilation.xlsx');
}
 ?>