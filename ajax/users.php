<?php 
	require '../config/conn.php';
	require '../modules/users.php';

	$users = new users();
	$id = '6';
	$str =  'alimentos usa';
	$op = "listar";

	switch ($_GET['op']) {
		case 'insert':
			$result = $users->insert($str);
			echo $result ? "registro insertado" : "error...";
			break;
		case 'alter':
			$result = $users->alter($id,$str);
			echo $result ? "registro actualizado" : "error...";
			break;
		case 'listar':
			$result = $users->listar();
			$data= Array();
	 		while ($reg=$result->fetch_object()){
	 			$data[]=array(
	 				"0"=>$reg->usuario,
	 				"1"=>$reg->name,
	 				"2"=>$reg->sections,
	 				"3"=>$reg->visits,
	 				"4"=>$reg->last_login
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