<?php
/* Value Object file for table Ingresos */

class Ingresos
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_ingreso = $data['id_ingreso'];
			$this->concepto = $data['concepto'];
			$this->monto = $data['monto'];
			$this->fecha = $data['fecha'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_ingreso
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id para identificar el ingreso
	  */
	protected $id_ingreso;

	/**
	  * concepto
	  * @var varchar(100) concepto en lo que se ingreso
	  */
	protected $concepto;

	/**
	  * monto
	  * @var float lo que costo este ingreso
	  */
	protected $monto;

	/**
	  * fecha
	  * @var timestamp fecha del ingreso
	  */
	protected $fecha;

	/**
	  * id_sucursal
	  * @var int(11) sucursal en la que se hizo el ingreso
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * @var int(11) usuario que registro el ingreso
	  */
	protected $id_usuario;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdIngreso()
	{
		return $this->id_ingreso;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdIngreso( $id_ingreso )
	{
		$this->id_ingreso = $id_ingreso;
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
