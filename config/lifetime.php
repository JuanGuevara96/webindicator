<?php
	$time = 3600; // 30 minutes en mili-segundos 
	// verificamos si existe la sesión 
	// el nombre "session_name" es como ejemplo 
	if(isset($_SESSION["user"])) 
	{ 
	      // verificamos si existe la sesión que se encarga del tiempo 
	      // si existe, y el tiempo es mayor que una hora, expiramos la sesión  
	      if(isset($_SESSION["expire"]) && time() > $_SESSION["expire"] + $time) 
	      { 
	           echo'<script type="text/javascript">alert("Your session has expired due to 1 hour to inactivity"); 
	           window.location.href="index.php";</script>';  
	       // también puedes utilizar header(“Location:index.php”);
	          
	     // destruir informacion de session en el server
	  
	          unset($_SESSION["expire"]); 
	          unset($_SESSION["user"]); 
	         //session_unset();
	         session_destroy(); 
	 
	// destruir informacion de session en el cliente 
	$session_cookie_params = session_get_cookie_params(); 
	setcookie(session_name(), '', time() - 24 * $time, $session_cookie_params['path'], $session_cookie_params['domain'], $session_cookie_params['secure'], $session_cookie_params['httponly']); 
	 
	// Limpiar el array $_SESSION
	//$_SESSION = array();  
	 
	      } 
	      // creamos o redefinimos el valor de la variable de session 
	      else
	      { 
	           $_SESSION["expire"] = time(); 
	       } 
	} 
?>