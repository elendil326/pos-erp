<?php
/* Value Object file for table Gastos */

class Gastos
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_gasto = $data['id_gasto'];
			$this->concepto = $data['concepto'];
			$this->monto = $data['monto'];
			$this->fecha = $data['fecha'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_gasto
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id para identificar el gasto
	  */
	protected $id_gasto;

	/**
	  * concepto
	  * @var varchar(100) concepto en lo que se gasto
	  */
	protected $concepto;

	/**
	  * monto
	  * @var float lo que costo este gasto
	  */
	protected $monto;

	/**
	  * fecha
	  * @var timestamp fecha del gasto
	  */
	protected $fecha;

	/**
	  * id_sucursal
	  * @var int(11) sucursal en la que se hizo el gasto
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * @var int(11) usuario que registro el gasto
	  */
	protected $id_usuario;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdGasto()
	{
		return $this->id_gasto;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdGasto( $id_gasto )
	{
		$this->id_gasto = $id_gasto;
	}

	/**
	  * @return varchar(100)
	  */
	final public function getConcepto()
	{
		return $this->concepto;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setConcepto( $concepto )
	{
		$this->concepto = $concepto;
	}

	/**
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
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

	/**
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

}
