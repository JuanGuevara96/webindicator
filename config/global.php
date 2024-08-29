<?php 
//Ip de la pc servidor de base de datos
define("DB_HOST","localhost");

//Nombre de la base de datos
define("DB_NAME", "dbwebindicator");

//Usuario de la base de datos
define("DB_USERNAME", "exelco");

//Contraseña del usuario de la base de datos
define("DB_PASSWORD", "Exelco123*");

//definimos la codificación de los caracteres
define("DB_ENCODE","utf8");

//oracle connection
define("ORCL_HOST","192.168.100.90");
define("ORCL_NAME", "ORCL");
define("ORCL_INSTEAD", "(DESCRIPTION=( ADDRESS_LIST= (ADDRESS = (PROTOCOL = TCP) (HOST = 10.0.1.4) (PORT=1521)))( CONNECT_DATA = (SERVICE_NAME = PDBinfofin.sub01192138581.vcnexelco.oraclevcn.com) ))");
define("ORCL_USERNAME", "INFOFIN");
define("ORCL_PASSWORD", "Passw0rd");
?>