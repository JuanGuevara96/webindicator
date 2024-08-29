<?php 
	require '../config/conn.php';
	require '../modules/accounts.php';

	$accounts = new accounts();
	$id = '6';
	$str =  'alimentos usa';
	$op = "listar";

	switch ($_GET['op']) {
		case 'insert':
			$result = $accounts->insert($str);
			echo $result ? "registro insertado" : "error...";
			break;
		case 'alter':
			$result = $accounts->alter($id,$str);
			echo $result ? "registro actualizado" : "error...";
			break;
		case 'listar':
			$result = $accounts->listar();
			$data= Array();
	 		while ($reg=$result->fetch_object()){
	 			$data[]=array(
	 				"0"=>$reg->idcompany,
	 				"1"=>$reg->idaccount,
	 				"2"=>$reg->op,
	 				"3"=>$reg->type,
	 				"4"=>($reg->presup)?'<span class="label bg-green">yes <span><i class="fas fa-check text-success"></i>':
	 				'<span class="label bg-red">no <span><i class="fas fa-times text-danger"></i>'
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