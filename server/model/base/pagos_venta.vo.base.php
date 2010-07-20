<?php
/* Value Object file for table PagosVenta */

class PagosVenta
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_pago = $data['id_pago'];
			$this->id_venta = $data['id_venta'];
			$this->fecha = $data['fecha'];
			$this->monto = $data['monto'];
		}
	}

	/**
	  * id_pago
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id de pago del cliente
	  */
	protected $id_pago;

	/**
	  * id_venta
	  * @var int(11) id de la venta a la que se esta pagando
	  */
	protected $id_venta;

	/**
	  * fecha
	  * @var date fecha de pago
	  */
	protected $fecha;

	/**
	  * monto
	  * @var float total de credito del cliente
	  */
	protected $monto;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdPago()
	{
		return $this->id_pago;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdPago( $id_pago )
	{
		$this->id_pago = $id_pago;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * @return date
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * @param date
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
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

}
