<?php 
	require '../config/conn.php';
	require '../modules/sections.php';

	$sections = new sections();
	$id = '6';
	$str =  'alimentos usa';
	$op = "listar";

	switch ($_GET['op']) {
		case 'insert':
			$result = $sections->insert($str);
			echo $result ? "registro insertado" : "error...";
			break;
		case 'alter':
			$result = $sections->alter($id,$str);
			echo $result ? "registro actualizado" : "error...";
			break;
		case 'listar':
			$result = $sections->listar();
			$data= Array();
	 		while ($reg=$result->fetch_object()){
	 			$data[]=array(
	 				"0"=>$reg->idsection,
	 				"1"=>$reg->section_name,
	 				"2"=>$reg->moneda,
	 				"3"=>($reg->status)?'<span class="label p-1 bg-success rounded-sm text-white">Activo</span>':
	 				'<span class="label p-1 bg-danger rounded-sm text-white">Inactivo</span>'
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