<?php
/* Value Object file for table Usuario */

class Usuario
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_usuario = $data['id_usuario'];
			$this->nombre = $data['nombre'];
			$this->usuario = $data['usuario'];
			$this->contrasena = $data['contrasena'];
			$this->id_sucursal = $data['id_sucursal'];
		}
	}

	/**
	  * id_usuario
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) identificador del usuario
	  */
	protected $id_usuario;

	/**
	  * nombre
	  * @var varchar(100) nombre del empleado
	  */
	protected $nombre;

	/**
	  * usuario
	  * @var varchar(50) Campo no documentado
	  */
	protected $usuario;

	/**
	  * contrasena
	  * @var varchar(128) Campo no documentado
	  */
	protected $contrasena;

	/**
	  * id_sucursal
	  * @var int(11) Id de la sucursal a que pertenece
	  */
	protected $id_sucursal;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return varchar(50)
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * @param varchar(50)
	  */
	final public function setUsuario( $usuario )
	{
		$this->usuario = $usuario;
	}

	/**
	  * @return varchar(128)
	  */
	final public function getContrasena()
	{
		return $this->contrasena;
	}

	/**
	  * @param varchar(128)
	  */
	final public function setContrasena( $contrasena )
	{
		$this->contrasena = $contrasena;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

}
