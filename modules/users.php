<?php 


Class users
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insert($nombre)
	{
		$sql="INSERT INTO users (section_name, status) 
		VALUES ('$nombre', '1')";
		return equery($sql);
	}

	//Implementamos un método para editar registros
	public function alter($idsection,$nombre)
	{
		$sql="UPDATE users SET section_name='$nombre' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementamos un método para desactivar categorías
	public function inactive($idsection)
	{
		$sql="UPDATE users SET status='0' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementamos un método para activar categorías
	public function active($idsection)
	{
		$sql="UPDATE users SET status='1' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function show($idsection)
	{
		$sql="SELECT * FROM users WHERE idsection='$idsection'";
		return oquery($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM users";
		return equery($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM users where status=1";
		return equery($sql);		
	}
}

 ?>