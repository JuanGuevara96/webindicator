<?php 
		$dir = $_GET['dir'];
		$file = $_GET['file'];
		ob_clean();
		$fichero = '../files/'.$dir.'/'.$file;
		if (file_exists($fichero)) {
		    header('Content-Description: File Transfer');
		    header('Content-Type: application/octet-stream');
		    header('Content-Disposition: attachment; filename="'.basename($fichero).'"');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . filesize($fichero));
		    readfile($fichero);
		    exit;
		}
		else
			//echo $fichero;
			 echo '<script type="text/javascript"> alert("file not exist");
	           window.location.href="../index.php";</script>';  
 ?>