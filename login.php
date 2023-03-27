<?php session_start(); //necesario para variable globales, $_SERVER

	if (isset($_SESSION['user'])) { //comprobacion si el user esta logeado
		header('Location: index.php');
	}
	require './config/conn.php';

	if($_SERVER['REQUEST_METHOD'] == 'POST') { // captura datos del user
		$user = filter_var(strtolower($_POST['user']), FILTER_SANITIZE_STRING); //evita injeccion de codigo
		$password = htmlspecialchars($_POST['password']);
		$password = hash('sha512', $password);  //desencriptado

		$errors = '';
		if (empty($user) or empty($password)) {
			$errors .= '<li> Fill all information </li>';
		} else { //else empty

			$resultado = lquery(sqllogin(),$user, $password);
		if ($resultado !== false) {
			$_SESSION['user'] = $user;
			equery("UPDATE users SET visits = visits+1 , last_login = '".date('Y-m-d H:i:s')."' WHERE usuario = '$user'");
			$_SESSION['expire']=time(); #tiempo inicializacion
			header('Location: index.php');
		} else {
			$errors .= '<li> Incorrect User/Password </li>';
		}

		}// end else empty 
	}

 require 'views/login.view.php';
 ?>