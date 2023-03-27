<?php 
require "../config/conn.php";
$er = htmlspecialchars($_POST['er']);
$date_r = date('Ym', strtotime(strclean($_POST["year"]."-".$_POST['month'])));
switch ($er) {
	case 'settgs':
		$idcompany = htmlspecialchars($_POST['company']);
		if (checkactivedate($idcompany)) {
			$indexren = htmlspecialchars($_POST['indexren']);
			$valnum = ($_POST['valnum']) ? htmlspecialchars(str_replace(",","",$_POST['valnum'])):0;
			$type_c = htmlspecialchars($_POST['rdtype']);
			if ($type_c == 'pymes') {
				$date_r =  date('Ym', strtotime(htmlspecialchars($_POST["year"]."-".$_POST['month']). " +1 month"));
			}
			$descrip = $type_c;
			$state = equery("INSERT INTO cfg_reports(idcompany, date_r, indexren, value, type_c, descrip) VALUES ('$idcompany', '$date_r', '$indexren', '$valnum', '$type_c', (select renc_descripcion from cfg_reports_ren where indexren = '$indexren' and info_r = '$descrip')) ON DUPLICATE KEY UPDATE value = '$valnum'");
			if (!$state) echo $state;	
		}
		else {
			echo "FECHA BLOQUEDA";
		}
		break;
	case 'ini':
		$arr = array("moneda", "capital", "intereses");
		$idcompany = 108;
		$indexren = 2;
		foreach ($arr as $type_c) {
		$descrip = $type_c;
		$valnum = isset($_POST[$type_c]) ? htmlspecialchars($_POST[$type_c]) : 0;
		$state = equery("INSERT INTO cfg_reports(idcompany, date_r, descrip, indexren, value, type_c) VALUES ('$idcompany', '$date_r', '$descrip', '$indexren', '$valnum', '$type_c') ON DUPLICATE KEY UPDATE value = '$valnum'");
		if (!$state) echo $state;
		}
		break;
	case 'mainShow':
		$arr = squery("SELECT value FROM cfg_reports WHERE idcompany = 108 AND type_c IN ('moneda','capital','intereses') AND date_r = '$date_r' ORDER BY type_c");
        echo json_encode($arr);
		break;
	case 'upload': //codigo a modificar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		   $fileName = $_FILES["file"]["tmp_name"];
    
    		if ($_FILES["file"]["size"] > 0) {
		     $file = fopen($fileName, "r");
		        
		        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
		            $sqlInsert = "INSERT into users (userId,userName,password,firstName,lastName)
		                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
		            //$result = mysqli_query($conn, $sqlInsert);
		            echo $sqlInsert;
		            // if (! empty($result)) {
		            //     $type = "success";
		            //     $message = "CSV Data Imported into the Database";
		            // } else {
		            //     $type = "error";
		            //     $message = "Problem in Importing CSV Data";
		            // }
		        }
		    }
		break;
	case 'ERxCompany':
		$idcompany= strclean($_POST['idcompany']);
		$report=600;
		// $result = oci_exec(sql_calcula($idcompany,$report,$date_r)); //consulta ejecutiva por fecha asignada
		// oci_free_statement($result);
		// unset($result);
		$result = oci_exec("Select infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO, renf_valor1 MES, renf_valor2 ACUM, TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON");
		$table = "<table class='table table-bordered'>
					<thead class='thead-light'><tr>
						<th>CONCEPTO</th>
						<th>MES</th>
						<th>ACUM</th>
					</tr></thead>
					<tbody>";
		while ($row = oci_fetch_array($result, OCI_ASSOC)) {
			$table.= "<tr>
						<td>".trim($row['CONCEPTO'])."</td>
						<td>".(number_format(round($row['MES']/1000)))."</td>
						<td>".(number_format(round($row['ACUM']/1000)))."</td>
					  </tr>";
		}
		$table.= "</tbody></table><span>".$row['FECHA']."</span>";
		oci_free_statement($result);
		echo $table;
		break;
	case 'activedate':		
		if (isset($_POST['sections']) && count($_POST['sections']) == count($_POST['months'])) {

			for ($i=0; $i < count($_POST['sections']); $i++) { 
				$activedate = $_POST['years'][$i].$_POST['months'][$i];
				$status = equery("update db_ctl set activedate = ".$activedate." where section = '".$_POST['sections'][$i]."'");
				if(!$status) echo $status."\n";
			}		
		}
		break;
}

function checkactivedate($idcompany){ //valida la fecha activa de la division
	global $date_r;
	if ($idcompany == 1000) { //excepciones de compañias
		$aux = "energeticos";
		$section = oquery("select activedate from db_ctl where section = '$aux'");
	}
	else if ($idcompany < 10){
		$idcompany = ($idcompany == 7) ? 6 : $idcompany;
		$section = oquery("select activedate from db_ctl where section = (select distinct(col_name) from cfg_reports_col where idcompany = '$idcompany')");
	}
	else {
		$section = oquery("select d.activedate from company c left join db_ctl d on c.section = d.section where idcompany = '$idcompany'");
	}
	if ($section['activedate'] == $date_r)
		return true;
	else
		return false;
}

 ?>