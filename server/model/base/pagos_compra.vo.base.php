<?php
/* Value Object file for table PagosCompra */

class PagosCompra
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_pago = $data['id_pago'];
			$this->id_compra = $data['id_compra'];
			$this->fecha = $data['fecha'];
			$this->monto = $data['monto'];
		}
	}

	/**
	  * id_pago
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) identificador del pago
	  */
	protected $id_pago;

	/**
	  * id_compra
	  * @var int(11) identificador de la compra a la que pagamos
	  */
	protected $id_compra;

	/**
	  * fecha
	  * @var date fecha en que se abono
	  */
	protected $fecha;

	/**
	  * monto
	  * @var float monto que se abono
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
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
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
