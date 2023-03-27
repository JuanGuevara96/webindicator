<?php 
		   $fileName = $_FILES["file"]["tmp_name"];
    
    		if ($_FILES["file"]["size"] > 0) {
		     $file = fopen($fileName, "r");
		        
		        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
		            $sqlInsert = "INSERT into users (userId,userName,password,firstName,lastName)
		                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
		            //$result = mysqli_query($conn, $sqlInsert);
		            echo $sqlInsert;

 ?>