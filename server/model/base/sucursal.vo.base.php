<?php
/* Value Object file for table Sucursal */

class Sucursal
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_sucursal = $data['id_sucursal'];
			$this->descripcion = $data['descripcion'];
			$this->direccion = $data['direccion'];
		}
	}

	/**
	  * id_sucursal
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) Identificador de cada sucursal
	  */
	protected $id_sucursal;

	/**
	  * descripcion
	  * @var varchar(100) nombre o descripcion de sucursal
	  */
	protected $descripcion;

	/**
	  * direccion
	  * @var varchar(200) direccion de la sucursal
	  */
	protected $direccion;

	/**
	  * es llave primara 
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

	/**
	  * @return varchar(100)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * @return varchar(200)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * @param varchar(200)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

}
