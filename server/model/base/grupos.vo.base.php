<?php
/* Value Object file for table Grupos */

class Grupos
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_grupo = $data['id_grupo'];
			$this->nombre = $data['nombre'];
			$this->descripcion = $data['descripcion'];
		}
	}

	/**
	  * id_grupo
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_grupo;

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
	final public function getIdGrupo()
	{
		return $this->id_grupo;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdGrupo( $id_grupo )
	{
		$this->id_grupo = $id_grupo;
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
