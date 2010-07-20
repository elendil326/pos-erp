<?php
/* Value Object file for table Permisos */

class Permisos
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_permiso = $data['id_permiso'];
			$this->nombre = $data['nombre'];
			$this->descripcion = $data['descripcion'];
		}
	}

	/**
	  * id_permiso
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_permiso;

	/**
	  * nombre
	  * @var varchar(45) Campo no documentado
	  */
	protected $nombre;

	/**
	  * descripcion
	  * @var varchar(45) Campo no documentado
	  */
	protected $descripcion;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdPermiso()
	{
		return $this->id_permiso;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdPermiso( $id_permiso )
	{
		$this->id_permiso = $id_permiso;
	}

	/**
	  * @return varchar(45)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(45)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return varchar(45)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * @param varchar(45)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
