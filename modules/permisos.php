<?php 


Class permisos
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insert($iduser, $idsection)
	{
		$sql="INSERT INTO permisos (iduser, idsection) 
		VALUES ('$iduser', '$idsection')";
		return equery($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function show($idsection)
	{
		$sql="SELECT * FROM permisos WHERE idsection='$idsection'";
		return oquery($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql=" SELECT u.ID, u.name, ifnull(UPPER(s.section_name),'NOTHING') section_name FROM users u LEFT JOIN permisos p ON u.ID = p.iduser LEFT JOIN sections s ON p.idsection = s.idsection";
		return equery($sql);		
	}

}

 ?>