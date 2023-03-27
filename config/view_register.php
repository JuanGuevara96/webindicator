
<?php session_start();
if (!isset($_SESSION['user']) || $_SESSION['user'] != 'admin')
		header('Location: ../index.php');

require "../views/header.view.php";
?>
	<div class="f-center">
		
		<h2>Agregar Usuario</h2>
	<div class="divBorder" style="padding: 8px;">
		<form id="register" method="post">
		ID<input type="text" name="ID"><br>
		Nombre<input type="text" name="nombre"><br>
		Contraseña<input type="text" name="pass"><br>
		Usuario<input type="text" name="user"><br>
		Seccion<input type="text" name="section"><br>
		<br><button type="submit" class="button" style="float: right;"> Capturar </button>
		</form>
	</div>	
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#register").submit(function(e) {
	      e.preventDefault();
	      register();
	    });
	});

	function register(){
	  $.ajax({
	      url: "config/register.php",
	      method: "post",
	      global: false,
	      data: $("#register").serialize(),
	      dataType: "text",
	      success: function(strmsg) {
	      	alert(strmsg);
	        // setTimeout(function(){// wait for n secs(2)
	        //       location.reload(); // then reload the page.(3)
	        //   }, 10);
	          // if (strmsg != "")  //insert msg (3 secs)
	          //   $('#msg-capture').append('<span class="fade-msg">Error! Captured Fails..</span>');
	          // else
	          //  $('#msg-capture').append('<span class="fade-msg">Captured Success!</span>');
	          // setTimeout(function(){// wait for n secs(2)
	          //      $('.fade-msg').fadeOut();// then reload the page.(3)
	          // }, 3000);
	          // tableERdata();
	      }
  	  });
	}
</script>
<?php 
//include_once "./views/footer.view.php";
 ?>