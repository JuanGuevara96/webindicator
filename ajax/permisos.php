<?php 
	require '../config/conn.php';
	require '../modules/permisos.php';

	$permisos = new permisos();
	$id = '6';
	$str =  'alimentos usa';
	$op = "listar";

	switch ($_GET['op']) {
		case 'insert':
			$result = $permisos->insert($str);
			echo $result ? "registro insertado" : "error...";
			break;

		case 'listar':
			$result = $permisos->listar();
			$data= Array();
	 		while ($reg=$result->fetch_object()){
	 			$data[]=array(
	 				"0"=>$reg->ID,
	 				"1"=>$reg->name,
	 				"2"=>$reg->section_name
	 				);
	 		}
	 		$arr = array(
	 			"sEcho"=>1, //Información para el datatables
	 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
	 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
	 			"aaData"=>$data);
	 		echo json_encode($arr);
			break;		
		default:
			# code...
			break;
	}
 ?>