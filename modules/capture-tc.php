<?php 
if (isset($_POST['op'])) {
require "../config/conn.php";
	switch ($_POST['op']) {
		case 'mostrar':
			$aaaa = htmlspecialchars($_POST['year']);
			$sql = "call SelSummary('currency', '$aaaa')";
			echo query_table($sql);
			break;
		case 'add':
			if (!empty($_POST['clave'])) {
				$clave = $_POST['clave'];
				$year = $_POST['year'];
				$index = $_POST['month'];
				$val = $_POST['moneda'];
				$sql = "INSERT INTO summary (clave, AAAA, column".$index.") VALUES('$clave','$year','$val') ON DUPLICATE KEY UPDATE column".$index." = '$val'";
				 equery($sql);
			}
			break;
		case 'selectCollection':
			$options="";
			$sql = "SELECT clave, descrip FROM collection WHERE collection = 'currency'";
			$select = squery($sql);
			foreach ($select as $item) {
				$options .= "<option value='".$item['clave']."'>".$item['descrip']."</option>";
			}
			echo $options;
			break;
	}
}


 ?>