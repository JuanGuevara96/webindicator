<?php 
require_once "oconn.php";

class ociFN extends ociDB
{
	function ociPM($idcompany, $date, $idaccount, $numpre){
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
		return $PM;
	}

	function oci_lastMov($idcompany,$idaccount, $auxlastmov){
		$lastmov = ''; 
		$oci = new ociDB();
		$oci->connect();
		$sql = 'select ctad_fecult CAMPO from ctb_cuentas where ctbs_cia = '.$idcompany.' AND ctac_cta = '.$idaccount;
		$lastmov = $oci->getRow($sql);
			if (strtotime($lastmov) < strtotime($auxlastmov)) { //compara fechas mayor o menor
				$lastmov = $auxlastmov;
			}
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

}


 ?>