<?php 
require 'config/conn.php';
include 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//$section = "alimentos mx";
$dateshow = "2019-11";//$_GET['dateshow'];
$tiempo_inicio = microtime_float();
			//clean jpg files
			//deletefiles($section);
			//spreadsheet start
			//$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('files\\data_compilation.xlsx');

//2020-ene-06 nueva version de metodo excel 
			
 $letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af');
function data($sheet, $idcompany,$report,$section, $clausula) { 

	$r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '$idcompany' and infi_reporte = '$report' $clausula order by infs_columna", OCI_ASSOC+OCI_RETURN_NULLS);
	
	$sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';
	for ($i=0; $i < count($r_col); $i++)
	$sql.=',renf_valor'.$r_col[$i]['COLUMNA'].' "'.$r_col[$i]['TITULO'].' '.$r_col[$i]['SUBTITULO'].'"';

	$sql .=" ,TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";
	unset($r_col);

	$highestColumn = $sheet->getHighestColumn();
	$highestRow = $sheet->getHighestRow();
	$result = oci_query($sql, OCI_ASSOC + OCI_RETURN_NULLS);

	//$colnames = [$section."-".$idcompany, 'CONCEPTO', 'AA', 'PPTO', 'FECHA'];
	//array_unshift($result, $colnames); //inserta colnames al inicio de la lista
	$sheet->fromArray(
        $result,   // The data to set
        NULL,        // Array values with this value will not be set
        'A'.$highestRow  // Top left coordinate of the worksheet range where we want to set these values (default is A1)
    );
	$highestRow++;


} //fin de metdo data**********************************************************************************

	//imprimir ajustes en xlsx
		global $letters;
		global $dateshow;
			//variables arrays
		$year = date('Y');
			
			//$dateshow = date('Y')."-".htmlspecialchars($_GET['month']);//$dateshow = $_SESSION['date'];
			
			$date = date('Ym', strtotime($dateshow));
			//clean jpg files

			//spreadsheet start
			$spreadsheet = new spreadsheet();
			$spreadsheet->getProperties()->setCreator("Webindicator");
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setTitle('Inicio');
			$sheet->setCellValue('A1', "Mes Cierre");
			$sheet->setCellValue('B1', date('m/Y', strtotime($dateshow)));
			$spreadsheet->addNamedRange(new NamedRange('MES_CIERRE', $spreadsheet->getSheet(0), 'B1'));


				//proyeccion reportes sig mes
			$list = array("BDER","ELIM");
			foreach ($list as $descrip) {

				$sheet = NewSheet($spreadsheet, $descrip."_sig");
				$date = date('Ym', strtotime($dateshow. " +1 month"));
				$reports = squery("SELECT idcompany, report, month, section FROM reports WHERE descrip = '$descrip' ORDER BY section, idcompany");
				foreach ($reports as $report) {
					extract($report);
					$clausula = ($idcompany != 305) ? "and infs_columna in (3,9)" : "and infs_columna in (3,5)";
					//oci_exec(sql_calcula($report['idcompany'],$report['report'],$date));
					data($sheet, $idcompany,$report,$section,$clausula);
				}
				unset($reports);
			}

			$list = array("BDER","ELIM");
			foreach ($list as $descrip) {
				$date = date('Ym', strtotime($dateshow));
				$x=1;
				$reports = squery("SELECT idcompany, report, month, section FROM reports WHERE descrip = '$descrip' ORDER BY section, idcompany");
				$sheet = NewSheet($spreadsheet, $descrip);
				foreach ($reports as $report) {
					$section = $report['section'];
					if ($report['month'] != "0") {
						$date = date('Y',strtotime($dateshow)).$report['month'];
					}
					$clausula = "";
					//oci_exec(sql_calcula($report['idcompany'],$report['report'],$date));
					data($sheet, $report['idcompany'],$report['report'],$section, $clausula);
				}
				unset($reports);
			}

			//imprimir ajustes 
			//año de inicio dinamico YYYY-ENERO
			$reports = squery("select t1.idcompany, t1.indexren, (t1.indexren + 15) indexrenAcum,  VAL, ACUM, t3.indexcol, t3.info_r
			FROM (select idcompany, descrip, indexren, SUM(value) ACUM from cfg_reports where (date_r between '$year'+'01' and '$date') and type_c = 'mes' group by idcompany, indexren) t1
			LEFT JOIN (select idcompany, indexren, value VAL from cfg_reports where date_r = '$date' 
			and type_c = 'mes' group by idcompany, indexren) t2
			ON (t1.indexren = t2.indexren and t1.idcompany=t2.idcompany) LEFT JOIN cfg_reports_col t3 ON (t1.idcompany=t3.idcompany)");
			
				$sheet = NewSheet($spreadsheet, "ajustes");

				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; //salto de renglones mes 
					$renAc = $reports[$i]['indexrenAcum'];
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
					$sheet->setCellValue($letters[$col].$renAc, $reports[$i]['ACUM']);
				}
				
			$reports = squery("select t1.idcompany, t1.indexren, (t1.indexren + 8) indexrenAcum, VAL, ACUM, t3.indexcol, t3.info_r
			FROM (select idcompany, descrip, indexren, type_c, SUM(value) ACUM from cfg_reports where (date_r between '$year'+'01' and '$date') 
			group by idcompany, indexren, type_c) t1
			LEFT JOIN (select idcompany, indexren,  type_c, value VAL from cfg_reports where date_r = '$date') t2
			ON (t1.indexren = t2.indexren and t1.idcompany=t2.idcompany and t1.type_c = t2.type_c) inner JOIN cfg_reports_col t3 
			ON (t1.idcompany=t3.idcompany and t1.type_c = t3.info_r) WHERE info_r in ('division', 'capital', 'intereses', 'moneda') order by idcompany, info_r, indexren");

				$sheet = NewSheet($spreadsheet, "division");
				
				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; //salto de renglones mes 
					$renAc = $reports[$i]['indexrenAcum'];
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
					$sheet->setCellValue($letters[$col].$renAc, $reports[$i]['ACUM']);
				}
		unset($reports);
		$mnext =  date('Ym',strtotime($dateshow. " +1 month"));
		$reports = squery("(select t1.idcompany, descrip, type_c, info_r, date_r, indexren, indexcol,  value VAL from cfg_reports t1 LEFT JOIN cfg_reports_col t2 ON (t1.idcompany=t2.idcompany) where type_c = 'pymes' and date_r = '$date') union
			(select t1.idcompany, descrip, type_c, info_r, date_r, indexren + 14, indexcol,  value VAL from cfg_reports t1 
			LEFT JOIN cfg_reports_col t2 ON (t1.idcompany=t2.idcompany) where type_c = 'pymes' and date_r = '$mnext')");
				
				$sheet = NewSheet($spreadsheet, "proyeccion");
				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; //salto de renglones mes 
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
				}
		unset($reports);
		$writer = new Xlsx($spreadsheet); //other option to writer xlsx 
		$writer->save('files/CORPORATIVO/data-pruebas.xlsx');

function NewSheet($spreadsheet, $title){
	$spreadsheet->createSheet(); //crear nueva hoja de excel
	$nSheets = $spreadsheet->getSheetCount();
	$sheet = $spreadsheet->getSheet($nSheets-1);
	$sheet->setTitle($title);
	return $sheet;
}


$tiempo_fin = microtime_float();
echo "<br>Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
//functions

 ?>