<?php
/* Value Object file for table Impuesto */

class Impuesto
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_impuesto = $data['id_impuesto'];
			$this->descripcion = $data['descripcion'];
			$this->valor = $data['valor'];
			$this->id_sucursal = $data['id_sucursal'];
		}
	}

	/**
	  * id_impuesto
	  * es llave primara 
	  * @var int(11) Campo no documentado
	  */
	protected $id_impuesto;

	/**
	  * descripcion
	  * @var varchar(100) Campo no documentado
	  */
	protected $descripcion;

	/**
	  * valor
	  * @var int(11) Campo no documentado
	  */
	protected $valor;

	/**
	  * id_sucursal
	  * @var int(11) Campo no documentado
	  */
	protected $id_sucursal;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdImpuesto()
	{
		return $this->id_impuesto;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdImpuesto( $id_impuesto )
	{
		$this->id_impuesto = $id_impuesto;
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
	  * @return int(11)
	  */
	final public function getValor()
	{
		return $this->valor;
	}

	/**
	  * @param int(11)
	  */
	final public function setValor( $valor )
	{
		$this->valor = $valor;
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
