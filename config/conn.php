<?php 
require "global.php";
//error_reporting(E_ERROR); //desactiva los mensajes de error de php
$conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
$oci = oci_connect(ORCL_USERNAME,ORCL_PASSWORD, ORCL_INSTEAD); //error, si tarda en conectar. 
if ((!$oci) || ($conn->connect_error)) {
 	echo'<script type="text/javascript">alert("Connection error of database, try it again");</script>'; 
 } 
mysqli_query( $conn, 'SET NAMES "'.DB_ENCODE.'"');

	function squery($sql)
	{
		global $conn;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$query = $conn->query($sql);		
		while($row = $query->fetch_assoc()){
			$push[] = $row;
		}
		if (isset($push)) 
			return $push;
		else 
			return Null;
	}

	function equery($sql)
	{
		global $conn;
		mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		mysqli_real_escape_string($conn,$sql);
		return $conn->query($sql);
	}

	function oquery($sql){
		//devuelve un solo registro
		$conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);
		//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
		$query = $conn->query($sql);		
		$row = $query->fetch_assoc();
		$query->free();
		$conn->close();
		if (isset($row)) 
			return $row;
		else 
			return Null;
	}

	function lquery($sql,$user,$password)
	{
		$pdoconn = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,DB_USERNAME,DB_PASSWORD); //connection PDO mysql
		$statement = $pdoconn->prepare($sql);
		$statement->execute(array(
			'usuario' => $user,
			'password' => $password
		)); 
		$statement->bindColumn('sections', $sections);
		$statement->bindColumn('name', $name);
		$result = $statement->fetch(PDO::FETCH_BOUND);
		$_SESSION['sections'] = $sections; 
		$_SESSION['name'] = $name;
		return $result;
	}

	function sqlclean($str) // verificar
	{
		global $conn;
		$str = mysqli_real_escape_string($conn,trim($str));
		return htmlspecialchars($str);
	}

	function strclean($str)
	{
		return htmlspecialchars(str_replace(' ', '', $str));
	}

	function sqllogin()
	{
		return 'SELECT usuario, pass, sections, name FROM users WHERE usuario = :usuario AND pass = :password';
	}
	function sqlpopup($Id)
	{
		return 'SELECT idaccount FROM accounts WHERE idcompany = '.$Id;
	}

	function sqlmov($idcompany,$idaccount, $auxlastmov){
		global $oci; $lastmov = ''; 
		$sql = 'select ctad_fecult from ctb_cuentas where ctbs_cia = '.$idcompany.' AND ctac_cta = '.$idaccount;
		$stmt = oci_parse($oci,$sql);
		oci_define_by_name($stmt, 'CTAD_FECULT', $lastmov);
		oci_execute($stmt);
		if(!oci_fetch($stmt))
		{echo "<br>ID: ".$idcompany ." cuenta: ". $idaccount;}
			if (strtotime($lastmov) < strtotime($auxlastmov)) { //compara fechas mayor o menor
				$lastmov = $auxlastmov;
			}
		//echo "<pre>";print_r($lastmov);
		oci_free_statement($stmt);
		return $lastmov;
	}

	function sqldata_pres(){ 
		return	'DECLARE 
		  PN_EMPRESA NUMBER;
		  PN_EJERCICIO_MES NUMBER;
		  PC_CUENTA CHAR(36);
		  PN_MONEDA NUMBER;
		  PN_INCMVTOSNOAF NUMBER;
		  PN_SALDOINI NUMBER;
		  PN_MOVNETOS NUMBER;
		  PN_CARGOS NUMBER;
		  PN_CREDITOS NUMBER;
		  PN_SALDOFIN NUMBER;
		  PN_AAMMSALDO NUMBER;
		  PC_NOMBRE_CTA VARCHAR2(200);
		  PN_NATURALEZA_CTA NUMBER;
		  PN_PRESUP_MENSUAL NUMBER;
		  PN_PRESUP_ACUMULADO NUMBER;
		  PC_MENSAJE_ERR VARCHAR2(200);
		  PC_NUMPRE VARCHAR2(200); 
		  BEGIN spctb_saldos_pak.p_calcula_saldo_y_movtos_cta(
		PN_EMPRESA=>:idcompany,PN_EJERCICIO_MES => :date, PC_CUENTA=>:idaccount, PN_MONEDA=>:moneda,PN_INCMVTOSNOAF=>:incmovnoafec,PN_SALDOINI=>:PN_SALDOINI,PN_MOVNETOS=>:movnetos,PN_CARGOS=>:cargos,PN_CREDITOS=>:creditos,PN_SALDOFIN=>:saldofin,PN_AAMMSALDO=>:PN_AAMMSALDO,PC_NOMBRE_CTA=>:PC_NOMBRE_CTA,PN_NATURALEZA_CTA=>:PN_NAT, PN_PRESUP_MENSUAL=>:pm, PN_PRESUP_ACUMULADO=>:PN_PRESUP_ACUMULADO, PC_MENSAJE_ERR=>:PC_MENSAJE_ERR, PC_NUMPRE=>:PC_NUMPRE); 
		END;';
	}
	function numabs($n){
		return ABS($n);
	}
	function miles($n){
		return $n = $n / 1000;
	}
	function half($n,$h){
		return $n = $n / $h;
	}
	function sum($row, $var2, $var1){
		if ($row == '1') {
			$var2 += $var1;
		}
		else if ($row == '0') {
			$var2 -= abs($var1);
		}
		return $var2;
	}
	function microtime_float()
	{
		list($useg, $seg) = explode(" ", microtime());
		return ((float)$useg + (float)$seg);
	}
	function ocifunction($idcompany, $date, $idaccount, $numpre){
		global $oci;
		$stmt = oci_parse($oci,sqldata_pres());

				$PM = array(
				':idcompany' => $idcompany,
				':date' => $date,
				':idaccount' => $idaccount,
				':moneda' => 0,
				':incmovnoafec' => 0,
				':PN_SALDOINI' => '',
				':movnetos' => '',
				':cargos' => '',
				':creditos' => '',
				':saldofin' => '',
				':PN_AAMMSALDO' => '',
				':PC_NOMBRE_CTA' => '',
				':PN_NAT' => '', 
				':pm' => '',
				':PN_PRESUP_ACUMULADO' => '',
				':PC_MENSAJE_ERR' => '',
				':PC_NUMPRE' => $numpre
				);

				foreach ($PM as $key => $value) {
					oci_bind_by_name($stmt, $key, $PM[$key],200); //warning buffer error
				}
				$oci_result = oci_execute($stmt);
				if (!$oci_result) {
					oci_execute($stmt); //query execute again for error spctb procedure.
					}
		oci_free_statement($stmt);			 
		return $PM;
	}

	function privileges($str){
		$arr = str_split($_SESSION['sections'], 3);
		foreach ($arr as $value) {
			if (strtoupper($value) == $str) {
					return true;
			}	
		}
		return false;
	}
	// function dateshow($date){
	// 	//date to month or diary
	// 	if (date('Ym') == date('Ym',strtotime($date))) {
	// 		return date('F j, Y');
	// 	}
	// 	else {
	// 		return date('F t, Y', strtotime($date));
	// 	}
	// }
	function pold_count($idcompany, $dateshow){
		$month = substr($dateshow, 0, 3);
		$year = substr($dateshow, -2, 2);
		global $oci;
		//*nota optimizar codigo
		if ($idcompany != 807) { 
			$sql = "select count(*) FPOLIZAS from ctb_polizas 
				where (POLY_STATUS=1 OR POLY_STATUS=0) and POLD_FECHA like '%".strtoupper($month)."-".$year."' and ctbs_cia = ".$idcompany;
		}
		else{
			$sql = "select count(*) FPOLIZAS from ctb_polizas 
				where (POLY_STATUS=1 OR POLY_STATUS=0) and POLD_FECHA like '%".strtoupper($month)."-".$year."' and (ctbs_cia = '807' OR ctbs_cia = '808' OR ctbs_cia = '812' OR ctbs_cia = '816' OR ctbs_cia = '817')";
		}
		$stmt = oci_parse($oci,$sql);
		oci_define_by_name($stmt, 'FPOLIZAS', $pold);
		oci_execute($stmt);
		oci_fetch($stmt);
		oci_free_statement($stmt);
		return $pold;
	}

	function sqlpold($idcompany, $dateshow){
		$month = substr($dateshow, 0, 3);
		$year = substr($dateshow, -2, 2);
		global $oci; $array = [];
		//*nota optimizar codigo
		if ($idcompany != 807) { 
		$sql = "SELECT poli_folio, pold_fecha, polm_totcar, polm_totcre, polc_origen, poly_status, nvl(polc_descrip,' ') polc_descrip
		FROM ctb_polizas 
		where ctbs_cia = '".$idcompany."' and pold_fecha like '%".strtoupper($month)."-".$year."' and (poly_status = 0 or poly_status = 1) ORDER BY pold_fecha";
		} else {
		$sql = "SELECT poli_folio, pold_fecha, polm_totcar, polm_totcre, polc_origen, poly_status, nvl(polc_descrip,' ') polc_descrip
		FROM ctb_polizas 
		where (ctbs_cia = '807' OR ctbs_cia = '808' OR ctbs_cia = '812' OR ctbs_cia = '816' OR ctbs_cia = '817') and pold_fecha like '%".strtoupper($month)."-".$year."' and (poly_status = 0 or poly_status = 1) ORDER BY pold_fecha";
		}
		$stmt = oci_parse($oci,$sql);
		oci_execute($stmt);
		    while ($row = oci_fetch_array($stmt, OCI_NUM)){
		      $array[] = $row;
		    }
		return $array;
	}

	function oci_exec($sql){
		global $oci;
		$stmt = oci_parse($oci,$sql);
		oci_execute($stmt);
		return $stmt;
	}
	function sql_calcula($idcompany,$rep,$date){
		return "DECLARE PN_IDIOMA NUMBER;EMPRESA NUMBER;NUMREP NUMBER;AAMM NUMBER;SALIDA NUMBER;
			BEGIN PN_IDIOMA := 1;EMPRESA := $idcompany;NUMREP := $rep;AAMM := $date;
			SPCP_CALCULO_EEFF(
		     PN_IDIOMA => PN_IDIOMA,
		     EMPRESA => EMPRESA,
		     NUMREP => NUMREP,
		     AAMM => AAMM,
		     SALIDA => SALIDA
		   ); END;";
	}

	function oci_query($sql, $mode){
		//modficado 2020-e e-06
		global $oci; //$push = array();
		$stmt = oci_parse($oci,$sql);
		oci_execute($stmt);
		while ($row = oci_fetch_array($stmt, $mode)) {
			$push[] = $row;
		}
		oci_free_statement($stmt);
		return (isset($push)) ? $push : null;

		//falta return null
	}

	function arrtoList($arr){
	//convierte array multidimensional en lista IN, [(1,2,3)]
		 $list="";
		 $arraymap = array_map('current', $arr);
		 foreach ($arraymap as $value) {
		 	$list .= ", '$value'";
		 }
		 return $list = substr($list, 1);
	}	 

	
	function my_error_handler($error_no, $error_msg){
		//message error format
	    echo "Opps, something went wrong:"
	    +"\nError number: [$error_no]"
	    +"\nError Description: [$error_msg]";
	}	
?>