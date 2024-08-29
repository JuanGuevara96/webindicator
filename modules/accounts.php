<?php 


Class accounts
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insert($idcompany, $account, $op, $type, $presup)
	{
		$sql="INSERT INTO accounts (idcompany, idaccount ,status) 
		VALUES ('$nombre', '1')";
		return equery($sql);
	}

	//Implementamos un método para editar registros
	public function alter($idsection,$nombre)
	{
		$sql="UPDATE sections SET section_name='$nombre' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementamos un método para desactivar categorías
	public function inactive($idsection)
	{
		$sql="UPDATE accounts SET status='0' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementamos un método para activar categorías
	public function active($idsection)
	{
		$sql="UPDATE accounts SET status='1' 
		WHERE idsection='$idsection'";
		return equery($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function show($idsection)
	{
		$sql="SELECT * FROM accounts WHERE idsection='$idsection'";
		return oquery($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM accounts";
		return equery($sql);		
	}
	//Implementar un método para listar los registros y mostrar en el select
	public function select()
	{
		$sql="SELECT * FROM accounts where status=1";
		return equery($sql);		
	}
}

 ?>