<?php
/* Value Object file for table Encargado */

class Encargado
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_usuario = $data['id_usuario'];
			$this->porciento = $data['porciento'];
		}
	}

	/**
	  * id_usuario
	  * es llave primara 
	  * @var int(11) Este id es el del usuario encargado de su sucursal
	  */
	protected $id_usuario;

	/**
	  * porciento
	  * @var float este es el porciento de las ventas que le tocan al encargado
	  */
	protected $porciento;

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
	  * @return float
	  */
	final public function getPorciento()
	{
		return $this->porciento;
	}

	/**
	  * @param float
	  */
	final public function setPorciento( $porciento )
	{
		$this->porciento = $porciento;
	}

}
