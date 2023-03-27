<?php 
session_start();
require '../config/conn.php';
include '../vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	//continuar con handler errors message !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$section = htmlspecialchars($_GET['section']);
$operation = htmlspecialchars($_GET['op']);
switch ($operation) {
	case 'data':
	set_time_limit(300);
		$year = htmlspecialchars($_GET['year']);
		$dateshow = $year."-".htmlspecialchars($_GET['month']);//$dateshow = $_SESSION['date'];
		$date = date('Ym', strtotime($dateshow));
		$dateIni = htmlspecialchars($_GET['year'])."01";
		//error status msg
		$status = ($date > date('Ym')) ? "Cannot process months later to the current month\n" : false;
		$status2 = ($status) ? false : preStatus($date);
		$status3 = ($status2 || $status) ? false : StatusCheck($section);
		if (!$status && !$status2 && !$status3) {
			//variables arrays
			$letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af');	

			//spreadsheet start
			$spreadsheet = new spreadsheet();
			$spreadsheet->getProperties()->setCreator("Webindicator");
			datastatic($spreadsheet,$dateshow);

			//proyeccion reportes sig mes
			$list = array("BDER","ELIM");
			foreach ($list as $descrip) {
				$date = date('Ym', strtotime($dateshow. " +1 month"));
				$x=1;
				$reports = squery("SELECT idcompany, report, month, section FROM reports WHERE descrip = '$descrip' ORDER BY section, idcompany");
				$spreadsheet->createSheet(); //crear nueva hoja de excel
				$nSheets = $spreadsheet->getSheetCount(); //numero de hojas del libro
				$sheet = $spreadsheet->getSheet($nSheets-1); //posicionar en ultima hoja del libro
				$sheet->setTitle($descrip."_sig"); //asignar nombre a hoja
				foreach ($reports as $report) { //ciclo de reportes
					$section = $report['section'];
					if ($report['month'] != "0") {
						$date = date('Y',strtotime($dateshow)).$report['month'];
					}
					$clausula = ($report['idcompany'] != 305) ? "and infs_columna in (3,9)" : "and infs_columna in (3,5)";
					//oci_exec(sql_calcula($report['idcompany'],$report['report'],$date));
					data($spreadsheet, $report['idcompany'],$report['report'],$section, $clausula);
				}
				unset($reports); //vacia el arreglo
			}
			$list = array("BDER","BDBG","BDFE","ELIM"); //lista, descripcion de reportes
			foreach ($list as $descrip) {
				$x=1;
				$date = date('Ym', strtotime($dateshow));
				$reports = squery("SELECT idcompany, report, month, section FROM reports WHERE descrip = '$descrip' ORDER BY section, idcompany");
				$spreadsheet->createSheet(); //crear nueva hoja de excel
				$nSheets = $spreadsheet->getSheetCount();
				$sheet = $spreadsheet->getSheet($nSheets-1);
				$sheet->setTitle($descrip);
				foreach ($reports as $report) {
					$section = $report['section'];
					if ($report['month'] != "0") {
						$date = date('Y',strtotime($dateshow)).$report['month'];
					}
					$clausula = "";
					// oci_exec(sql_calcula($report['idcompany'],$report['report'],$date)); //consulta ejecutiva por fecha asignada
					data($spreadsheet, $report['idcompany'],$report['report'],$section, $clausula);
				}
				unset($reports);
			}

			//imprimir ajustes 
			$reports = squery("select t1.idcompany, t1.indexren, (t1.indexren + 15) indexrenAcum,  VAL, ACUM, t3.indexcol, t3.info_r
			FROM (select idcompany, descrip, indexren, SUM(value) ACUM from cfg_reports where (date_r between '$dateIni' and '$date') and type_c = 'mes' group by idcompany, indexren) t1
			LEFT JOIN (select idcompany, indexren, value VAL from cfg_reports where date_r = '$date' 
			and type_c = 'mes' group by idcompany, indexren) t2
			ON (t1.indexren = t2.indexren and t1.idcompany=t2.idcompany) LEFT JOIN cfg_reports_col t3 ON (t1.idcompany=t3.idcompany) where info_r = 'mes'");
			
			$spreadsheet->createSheet();
			$nSheets = $spreadsheet->getSheetCount();
			$sheet = $spreadsheet->getSheet($nSheets-1);
			$sheet->setTitle('ajustes');

				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; 
					$renAc = $reports[$i]['indexrenAcum']; //salto de renglones mes 
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
					$sheet->setCellValue($letters[$col].$renAc, $reports[$i]['ACUM']);
				}
			unset($reports);	
			$reports = squery("select t1.idcompany, t1.indexren, (t1.indexren + 8) indexrenAcum, VAL, ACUM, t3.indexcol, t3.info_r
			FROM (select idcompany, descrip, indexren, type_c, SUM(value) ACUM from cfg_reports where (date_r between '$dateIni' and '$date') 
			group by idcompany, indexren, type_c) t1
			LEFT JOIN (select idcompany, indexren,  type_c, value VAL from cfg_reports where date_r = '$date') t2
			ON (t1.indexren = t2.indexren and t1.idcompany=t2.idcompany and t1.type_c = t2.type_c) inner JOIN cfg_reports_col t3 
			ON (t1.idcompany=t3.idcompany and t1.type_c = t3.info_r) WHERE info_r in ('division', 'capital', 'intereses', 'moneda') order by idcompany, info_r, indexren");
			$spreadsheet->createSheet();
			$nSheets = $spreadsheet->getSheetCount();
			$sheet = $spreadsheet->getSheet($nSheets-1);
			$sheet->setTitle('division'); 
				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; //salto de renglones mes 
					$renAc = $reports[$i]['indexrenAcum'];
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
					$sheet->setCellValue($letters[$col].$renAc, $reports[$i]['ACUM']);
				}
		unset($reports);
		//reporte proyeccion del mes
		$mnext =  date('Ym',strtotime($dateshow. " +1 month"));
		$reports = squery("(select t1.idcompany, descrip, type_c, info_r, date_r, indexren, indexcol,  value VAL from cfg_reports t1 LEFT JOIN cfg_reports_col t2 ON (t1.idcompany=t2.idcompany) where type_c = 'pymes' and date_r = '$date') union
			(select t1.idcompany, descrip, type_c, info_r, date_r, indexren + 15, indexcol,  value VAL from cfg_reports t1 
			LEFT JOIN cfg_reports_col t2 ON (t1.idcompany=t2.idcompany) where type_c = 'pymes' and date_r = '$mnext')");
			$spreadsheet->createSheet();
			$nSheets = $spreadsheet->getSheetCount();
			$sheet = $spreadsheet->getSheet($nSheets-1);
			$sheet->setTitle('proyeccion');//setTitle($idcompany.'-'.$report);
				for ($i=0; $i < count($reports); $i++) { 
					$col = $reports[$i]['indexcol']-1;
					$ren =  $reports[$i]['indexren']; //salto de renglones mes 
					$sheet->setCellValue($letters[$col].$ren, $reports[$i]['VAL']);
				}
		unset($reports);
		$writer = new Xlsx($spreadsheet); //other option to writer xlsx 
		$writer->save('../files/CORPORATIVO/data.xlsx');
		}
		else 
			echo $status;
			echo $status2;
			echo $status3;
		break;
	case 'macro': 
		//clean jpg files
		deletefiles($section);
		$dateright = substr(htmlspecialchars($_GET['year']), 2);
		$macrokey = substr($section,0,3)."-". $dateright;
		//exec('C:\Users\Administrador\Documents\ConsoleExcel\bin\Debug\ConsoleExcel.exe "inetpub\wwwroot\webindicator\files" "'.$section.'" "Div-'.$macrokey.'.xlsm"');
		break;
	case 'readimages':
		$list = array();
		$files = glob("{../files/".$section."/data/}*.{png,html}", GLOB_BRACE); //ruta actual
		foreach ($files as $file) {
			$rfile = explode("/", $file);
		    $ufile = substr($rfile[4], 0, -4);
		    if (strlen(iconv("UTF-8", "UTF-8//IGNORE", $ufile)) != strlen($ufile))
				$ufile = utf8_encode($ufile);
		    array_push($list, $ufile);
		}
		sort($list,SORT_NUMERIC);
		echo json_encode($list, JSON_UNESCAPED_UNICODE );
	break;
	case 'lastrun':
		$q = squery("select lastrun from db_ctl where section = '$section'");
		echo $q[0]['lastrun'];
	break;
	case 'update':
		equery("UPDATE db_ctl SET status = 0 WHERE section = '".$section."'");
	break;
	case 'release':
		$files = glob("{../files/".$section."/data/}*.{html}", GLOB_BRACE); //ruta actual
		if (!empty($files)) {
			$rfile = explode("/", $files[0]);
		    $ufile = substr($rfile[4], 0, -4);
		    echo $ufile;
		}
	break;
	case 'queryER':
	$idcompany = htmlspecialchars($_GET['idcompany']);
	$date_r = htmlspecialchars($_GET['year']).htmlspecialchars($_GET['month']); //nMont format MM numero mes
	$dateIni = htmlspecialchars($_GET['year'])."01";
	$type_c = htmlspecialchars($_GET['type_c']);
		if ($type_c == 'pymes')
			$date_r = date('Ym', strtotime(htmlspecialchars($_GET['year'])."-".htmlspecialchars($_GET['month'])." +1 month"));
	$table = "";
	$arr = squery("select t1.descrip, t1.indexren, ifnull(round(VAL,2), 0) VAL, ifnull(round(ACUM,2),0) ACUM
		FROM (select descrip, indexren, SUM(value) ACUM from cfg_reports where (date_r between '$dateIni' and '$date_r') and idcompany = '$idcompany' and type_c = '$type_c' group by idcompany, indexren) t1
		LEFT JOIN (select descrip, indexren, value VAL from cfg_reports where date_r = '$date_r' 
		and idcompany = '$idcompany' and type_c = '$type_c' group by idcompany, indexren) t2
		ON (t1.indexren = t2.indexren)");
		for ($i=0; $i < count($arr); $i++) { 
			$table .= "<tr><td>".$arr[$i]['descrip']."</td>
			<td>".$arr[$i]['VAL']."</td>
			<td>".$arr[$i]['ACUM']."</td>
			<td>".$arr[$i]['VAL']."</td></tr>";
		}
	echo $table;
	break;
	case 'renglones':
	$html = "";
	$info_r = htmlspecialchars($_GET['type']); 
	$renglones = squery("select renc_descripcion as renglon, indexren from cfg_reports_ren where info_r = '$info_r' order by indexren");
	for ($i=0; $i < count($renglones); $i++) { 
             $html .= "<option value='".$renglones[$i]['indexren']."'> ".$renglones[$i]['renglon']." </option>";
            } 
    unset($renglones);
    echo $html; 
	break;
	case 'companies':
	$html = "";
	$info_r = htmlspecialchars($_GET['type']); 
	$renglones = squery("select col_name as renglon, indexcol, idcompany from cfg_reports_col where info_r = '$info_r' order by indexcol");
	for ($i=0; $i < count($renglones); $i++) { 
             $html .= "<option value='".$renglones[$i]['idcompany']."'> ".$renglones[$i]['renglon']." </option>";
            } 
    unset($renglones);
    echo $html; 
	break;
}


function data($spreadsheet, $idcompany,$report,$section, $clausula) {
	$oci = oci_connect("INFOFIN", "Passw0rd", "(DESCRIPTION=( ADDRESS_LIST= (ADDRESS = (PROTOCOL = TCP) (HOST = 10.0.1.3) (PORT=1521)))( CONNECT_DATA = (SERVICE_NAME = DB01_iad22z.sub01192138581.vcnexelco.oraclevcn.com) ))");
if (!$oci) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
	// sentencia sql reportes
	$r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '$idcompany' and infi_reporte = '$report' $clausula order by infs_columna", OCI_ASSOC+OCI_RETURN_NULLS);
	 $sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';


	for ($i=0; $i < count($r_col); $i++)

	$sql.=',renf_valor'.$r_col[$i]['COLUMNA'].' "'.$r_col[$i]['TITULO'].' '.$r_col[$i]['SUBTITULO'].'"';

	$sql .=" ,TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";


	global $letters;

$nSheets = $spreadsheet->getSheetCount();
$sheet = $spreadsheet->getSheet($nSheets-1);
//$sheet->setTitle($report);//setTitle($idcompany.'-'.$report);
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
}

function datastatic($spreadsheet, $dateshow) {
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Inicio');
	$sheet->setCellValue('A1', "Mes Cierre");
	$sheet->setCellValue('B1', date('m/Y', strtotime($dateshow)));
	$spreadsheet->addNamedRange(new NamedRange('MES_CIERRE', $spreadsheet->getSheet(0), 'B1'));

}

function deletefiles($section) {
	$files = glob("{../files/".$section."/data/}*", GLOB_BRACE);
	foreach ($files as $file) {
		utf8_encode($file);
	    unlink($file);
	}
}

function StatusCheck($section){
		$q = squery("select status, lastrun from db_ctl where section = '$section'");
		$date1 = new DateTime($q[0]['lastrun']);
		$date2 = date("Y-m-d H:i:s");
		$diff = $date1->diff(new DateTime($date2));
		$minutes = ( ($diff->days * 24 ) * 60 ) + ( $diff->i );
		if (($q[0]['status'] == '1') && ($minutes < 2)) {
			return "another user is processing data, please try it later in ". (2 - $minutes)." minutes";
		}
		else {
			equery("UPDATE db_ctl SET status = 1, lastrun = '".$date2."' WHERE section = '".$section."'");
			return false;
		}
}
function preStatus($date){
	$result = oquery("SELECT sum(if(value > 0, 1, 0)) value FROM cfg_reports WHERE date_r = '$date' and type_c IN ('moneda','capital','intereses')");
	 return ($result['value'] == 3) ? false : "Error! missing capture (intereses, capital, moneda)\n";
	 
}

 ?>