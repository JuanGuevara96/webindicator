<?php 
if (isset($_POST['op'])) {
require "../config/conn.php";
	switch ($_POST['op']) {
		case 'pold_count':
			$date = $_POST['date'];
			$idcompanies = $_POST['idcompanies'];
			$oci = new ociDB();
			$oci->connect();
			$list = arrtoList($idcompanies);
			$sql = "select ctbs_cia, count(poli_folio) c_polizas from ctb_polizas 
			where (POLY_STATUS=1 OR POLY_STATUS=0) and ctbs_cia IN(".$list.") and POLD_FECHA like '%".$date."' group by ctbs_cia";
			$result = $oci->getRows($sql);
			$oci->close();
			echo json_encode($result);
		break;
		case 'lastNetMov':
			$idcompanies = $_POST['idcompanies'];
			$result = array();
			$oci = new ociDB();
			$oci->connect();
			foreach ($idcompanies as $item) {
				$acc = squery("SELECT idaccount FROM 
					accounts WHERE type = 'I' AND op >= 0 AND idcompany = '$item'");
				$list = MultiArrtoList($acc, 'idaccount');
				$sql = "SELECT ctbs_cia, max(ctad_fecult) ctad_fecult FROM ctb_cuentas WHERE ctbs_cia = '$item' AND ctac_cta IN (".$list.") GROUP BY ctbs_cia";
				$result = array_merge($result, $oci->getRows($sql));
			}
			$oci->close();
			echo json_encode($result);
		break;
	}
}


 ?>

