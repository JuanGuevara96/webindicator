<?php session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin')
		header('Location: ../index.php');

require "conn.php" ;
		//extract(array_map('strclean', $_POST));
		if (!empty($_POST['user']) && !empty($_POST['pass'])) {
			$user = strclean($_POST['user']); 
			$pass = strclean($_POST['pass']);
			$password = hash('sha512',$pass);
			$nombre = htmlspecialchars($_POST['nombre']);
		    $section = "";
			if (!empty($_POST['sections'])) {
				$sections = $_POST['sections'];
			    for ($i=0; $i<count($sections); $i++) {
			        $section .= sqlclean($sections[$i]);
				}
			} 		
			$sql = "INSERT INTO users (ID,usuario,pass,sections,name,visits) VALUES ('$ID','$user','$password','$section','$nombre','0') ON DUPLICATE KEY UPDATE sections = '$section'";
			$status = equery($sql);		
			if ($status) {
				echo "Captura correcta";
			} else{
				echo htmlspecialchars(preg_replace ('/<[^>]*>/', ' ', $status)); //codigo depurar
			}
		}
		else
			echo "Error! Asigne un nombre de usuario y/o contraseña";

?>