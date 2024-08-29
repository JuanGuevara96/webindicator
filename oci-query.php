 <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
 	<script src="js/jquery-3.4.0.min.js"></script>
	<script src="js/all.js"></script>
	<script src="js/jszip.min.js"></script>
	<script src="js/pptxgen.min.js"></script>
	<script src="js/promise.min.js"></script>
<?php 
require './config/conn.php';
include 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\NamedRange;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// $sql = "select idcompany from company where section = 'ALIMENTOS MX'";
// $array = squery($sql);
// foreach ($array as $id) {
// 	echo $id['idcompany'];
// }		
	 		 $section = "CORPORATIVO";
	// 		// $dir = glob("{files/".$section."/}*.{xlsm,pptx}", GLOB_BRACE); //ruta actual
	// 		// foreach ($dir as $nombre_fichero) {
	// 		// 		$string = explode("/", $nombre_fichero);
	// 		// 	    echo $string[count($string)-1]. "\n";
	// 		// 	}
	// $dateshow = "Jun 12, 2018";
	// // $dateshow = date('Ym', strtotime($dateshow));
	// // $dateshow = substr($dateshow, 0, 4).'12';
	// // echo $dateshow;
	// // $dateshow = "201905";
	// // oci_exec(sql_calcula(816,600,substr($dateshow, 0, 4).'12'));
	// 	$companies = squery("select idcompany from company where section = '$section'");
	// 	$reports = array('600','650','610','601');
	// 	foreach ($companies as $company) {
	// 		foreach ($reports as $report) {
	// 			$date = date('Ym', strtotime($dateshow));
	// 			if ($report == "601") {
	// 				$date = date('Y',strtotime($dateshow)).'12';
	// 				echo $date;
	// 			}
	// 			//oci_exec(sql_calcula($company['idcompany'],$report,$date));
	// 		}
	// 	}
	// if (exec('C:\xampp\htdocs\webindicator\files\INMOBILIARIA\ru.bat')) {
	// 	echo "success";
	// }
	// else {echo "error";}
	//echo exec('C:\xampp\htdocs\webindicator\files\INMOBILIARIA\run.bat');
	 //exec('tasklist /FI "ImageName eq EXCEL.exe" /FI "Status eq Running" /FO CSV 2>NUL', $task_list);
	 //print_r($task_list);

	 //echo __DIR__.'\files\$section';
		// equery("UPDATE db_ctl SET status = 1, lastrun = '".date("Y-m-d H:i:s")."' WHERE section = '".$section."'");

		#$q = squery("select status, lastrun from db_ctl where section = '$section'");
		// $date1 = new DateTime($q[0]['lastrun']);
		// $date2 = date("Y-m-d H:i:s");
		// $diff = $date1->diff(new DateTime($date2));
		// $minutes = ( ($diff->days * 24 ) * 60 ) + ( $diff->i );
		// if (($q[0]['status'] == '1') && ($minutes < 2)) {
		// 	echo "another user is processing data, please try it later in ". (2 - $minutes)." minutes";
		// }
		// else {
		// 	echo "executed";
		// 	equery("UPDATE db_ctl SET status = 1, lastrun = '".$date2."' WHERE section = '".$section."'");
		// }
		// echo "<br>";
	 	//include "files/USA ALIMENTOS/data/2.html";

# codigo para nuevo metodo de tablas
	 		// $arr = squery("select * from company");
	 		// print_r($arr);
	 		// for ($i=0; $i < count($arr); $i++) { 
	 		// #array associative name las convierte en variables !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 		// extract($arr[$i], EXTR_OVERWRITE); 
	 		// echo "<br>".$idcompany." ".$company." ".$divisor." ".$section;
	 		// $arr2 = array();
	 		// $result = compact("idcompany","company",$arr2); #las vaiables las convierte array
	 		// print_r($result);
	 		// }
	 		$idcompany = 201;
	 		$report = 600;
	 		$renglon = 50;
	 		//$sql = "Select  RENC_DESCRIPCION CONCEPTO, renf_valor1 SI_ANO, renf_valor2 CaEnero,  renf_valor3 CrEnero,  renf_valor4 CaFebrero,  renf_valor5 CrFebrero,  renf_valor6 CaMarzo,  renf_valor7 CrMarzo, renf_valor8 SalMarzo,  renf_valor9 CaAbril,  renf_valor10 CrAbril,renf_valor11 CaMayo,  renf_valor12 CrMayo, renf_valor13 CaJunio, renf_valor14 CrJunio, renf_valor15 SalJunio,renf_valor16 CaJulio,renf_valor17 CrJulio,renf_valor18 CaAgosto,renf_valor19 CrAgosto, renf_valor20 CaSeptiebre, renf_valor21 CrSeptiembre,renf_valor22 SalSeptiembre,renf_valor23 CaOctubre,renf_valor24 CrOctubre,renf_valor25 CaNoviembre,renf_valor26 CrNoviembre,renf_valor27 CaDiciembre,renf_valor28 CrDiciembre, renf_valor29 SalDiciembre,   TO_CHAR(REPD_FECHAULTMOD,'MON-YYYY')  FECHA  FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany."AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";
	 	// 	$sql = "Select Sum(renf_valor1) Mes, Sum(renf_valor2) Acum,  Sum(renf_valor3) AA,  Sum(renf_valor4) Ppto FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE  AND A.INFI_REPORTE= 600 
	 	// 		AND ( ) AND infs_renglon = ".$renglon."
			// 	ORDER BY A.INFS_RENGLON";

			// $companies = squery("SELECT idcompany, section FROM reports WHERE report = 600 ORDER BY idcompany, section");
			// $length = count($companies);

			// $sectionaux = $companies[0]['section'];
			// $where = "";
			// //print_r($companies);
			// #foreach ($companies as $company) {
			// for ($i=0; $i < $length; $i++) { 
				
			// 	$where .= " A.ctbs_cia = ".$companies[$i]['idcompany'];
			// 	if($companies[$i]['section'] != $sectionaux){
			// 		$sql = $where;
			// 		$where = "";
			// 		echo "<br><br>".$sql;
			// 	} 
			// 		if ($i+1 != $length)
			// 			$sectionaux = $companies[$i+1]['section'];
			// 		//echo "<br><br>".$sql;
			// }


	 // 	global $oci;
	 // 	$stmt = oci_parse($oci,$sql);
		// oci_execute($stmt);
		// while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
		// 	//$push[] = $row;
		// 	print_r($row); echo "<br><br>";
		// }
		// oci_free_statement($stmt);
	 	// print_r($push);

//obtiene mes siguiente!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	$datestatic = "2019";
	// $date =  date('Ym',strtotime($datestatic. " +1 month"));
	// echo $date;

/// pruebas arraytoList !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	 // $companies = squery("select * from company where section = 'alimentos mx' order by idcompany, section");
	 // $list = arrtoList($companies);

	 // // $result = oci_query("select ctbs_cia EMPRESA, infs_columna as COLUMNA, lower(colc_titulo) as TITULO, colc_subtitulo SUBTITULO from inf_columnas where infs_columna IN (1,2,9) and infi_reporte = 600 and ctbs_cia IN ($list)");
	 
	 // //echo "<pre>";print_r($result);
	 // echo "<pre>";print_r($companies);

// $letters=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','aa','ab','ac','ad','ae','af');

	//nombres de columnas reporte
	// for ($j=0; $j < $ncol; $j++) { 
	// 	$name = oci_field_name($stmt, $j+1);
	// 	$sheet->setCellValue($letters[$j].$x, $name);
	// }


// 	$r_col = oci_query("select infs_columna as COLUMNA, colc_titulo as TITULO, colc_subtitulo SUBTITULO from inf_columnas where ctbs_cia = '203' and infi_reporte = '600' order by infs_columna");
// 	 $sql = 'Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO';

// 	for ($i=0; $i < count($r_col); $i++)

// 	$sql.=',renf_valor'.$r_col[$i]['COLUMNA'].' "'.$r_col[$i]['TITULO'].' '.$r_col[$i]['SUBTITULO'].'"';

// 	$sql .=" ,TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia ='203' AND A.INFI_REPORTE='600' ORDER BY A.INFS_RENGLON";
// 	unset($r_col);
// 	$result = oci_query($sql);

// 		$spreadsheet = new spreadsheet();
// 		$sheet = $spreadsheet->getActiveSheet();
// 		$spreadsheet->getActiveSheet()
//     ->fromArray(
//         $result,   // The data to set
//         NULL,        // Array values with this value will not be set
//         'A1'         // Top left coordinate of the worksheet range where
//                      //    we want to set these values (default is A1)
//     );

//     //codigo color en celdas 
//     // $spreadsheet->getActiveSheet()->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('ffffff');
		
// 		$writer = new Xlsx($spreadsheet); 
// 		$writer->save('files/CORPORATIVO/data-pruebas.xlsx');
// 	$highestColumn = $sheet->getHighestColumn();
// 	echo $highestColumn;
// echo "<pre>";print_r($result);


// $dateright = "20";//substr(htmlspecialchars($_GET['year']), 2);
// $macrokey = substr($section,0,3)."-". $dateright;
// exec('C:\Users\Administrador\Documents\ConsoleExcel\bin\Debug\ConsoleExcel.exe "inetpub\wwwroot\webindicator\files" "'.$section.'" "Div-'.$macrokey.'.xlsm"');
// echo 'C:\Users\Administrador\Documents\ConsoleExcel\bin\Debug\ConsoleExcel.exe "inetpub\wwwroot\webindicator\files" "'.$section.'" "Div-'.$macrokey.'.xlsm"';
session_start();
// $arr = str_split($_SESSION['sections'], 3);
// array_push($arr, "REA");
// print_r($arr);
echo date('l jS \of F Y h:i:s A');

 ?>