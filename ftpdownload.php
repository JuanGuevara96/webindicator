<?php 
#DOWNLOAD FILE
// FTP server details
$ftpHost   = 'ftp.example.com';
$ftpUsername = 'ftpuser';
$ftpPassword = '*****';

// open an FTP connection
$connId = ftp_connect($ftpHost) or die("Couldn't connect to $ftpHost");

// login to FTP server
$ftpLogin = ftp_login($connId, $ftpUsername, $ftpPassword);

// local & server file path
$localFilePath  = 'index.php';
$remoteFilePath = 'public_html/index.php';

// try to download a file from server
if(ftp_get($connId, $localFilePath, $remoteFilePath, FTP_BINARY)){
    echo "File transfer successful - $localFilePath";
}else{
    echo "There was an error while downloading $localFilePath";
}

// close the connection
ftp_close($connId);

 ?>

 <?php
 	#EXTENSION FILE 
	$formatos_permitidos =  array('doc','docx' ,'xls');
	$archivo = $_FILES['doc_file']['name'];
	$extension = pathinfo($archivo, PATHINFO_EXTENSION);
	if(!in_array($extension, $formatos_permitidos) ) {
	    echo 'Error formato no permitido !!';
	}

  ?>