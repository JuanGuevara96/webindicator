<?php 
require "../config/conn.php";
// require "../config/oconn.php";
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
		// $arr = array("moneda", "capital", "intereses");
		$arr = array("capital", "intereses");
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
	case 'ERxCompany':
		$idcompany = strclean($_POST['idcompany']);
		$report=600;
		//$result = oci_exec(sql_calcula($idcompany,$report,$date_r)); //consulta ejecutiva por fecha asignada
		//oci_free_statement($result);
		//unset($result);
		$oci = new ociDB();
		$oci->connect();
		// print_r($oci->getErrors());
		$sql = "SELECT infs_renglon RENGLON, RENC_DESCRIPCION CONCEPTO, renf_valor1 MES, renf_valor2 ACUM, TO_CHAR(REPD_FECHAULTMOD,'MM-YYYY') FECHA FROM INF_RENGLONES A, INF_REPORTES B where A.CTBS_CIA = B.CTBS_CIA AND A.INFI_REPORTE = B.INFI_REPORTE AND A.ctbs_cia =".$idcompany." AND A.INFI_REPORTE=".$report." ORDER BY A.INFS_RENGLON";
		$result = $oci->getRows($sql, OCI_NUM);
		$table = "";
		foreach ($result as $row) {
			$table .= "<tr>
						<td id='".trim($row[0])."'>".trim($row[1])."</td>
						<td>".(number_format(round($row[2]/1000)))."</td>
						<td>".(number_format(round($row[3]/1000)))."</td>
					  </tr>";
		}
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
	case 'property':
		$idcompany= strclean($_POST['idcompany']);
		$result = oquery("select asset from company where idcompany = '$idcompany'");
		if($result) echo "property ".$result['asset']."%";	
		break;
	case 'dataER':
		$idcompany = htmlspecialchars($_POST['idcompany']);
		if (checkactivedate($idcompany)) {

		$type_c = htmlspecialchars($_POST['category']);
		$sql = "";
			foreach ($_POST[$idcompany] as $renglon => $valor) {
				if (!empty($valor)) {
					$sql = "INSERT INTO cfg_reports(idcompany, date_r, type_c, indexren, value, descrip) VALUES('$idcompany', '$date_r', '$type_c', '$renglon', '$valor', (select renc_descripcion from cfg_reports_ren where indexren = '$renglon' and info_r = '$type_c')) ON DUPLICATE KEY UPDATE value = '$valor'";
					$state = equery($sql);
					if (!$state) echo $state;
					else echo "save success...\n";	
				}
			}
		} 
		else {
			echo "FECHA BLOQUEDA";
		}
		break;
}

function checkactivedate($idcompany){ //valida la fecha activa de la division
	global $date_r;

	//$flag = 1 : idcompany | 0 : idsection
	//$sql = "call Sel_activedate('$id', '$flag')";
	
	if ($idcompany == 1000) { //excepciones de compañias, modificar!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
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