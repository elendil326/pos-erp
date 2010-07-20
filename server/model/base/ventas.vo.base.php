<?php
/* Value Object file for table Ventas */

class Ventas
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_venta = $data['id_venta'];
			$this->id_cliente = $data['id_cliente'];
			$this->tipo_venta = $data['tipo_venta'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_venta
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id de venta
	  */
	protected $id_venta;

	/**
	  * id_cliente
	  * @var int(11) cliente al que se le vendio
	  */
	protected $id_cliente;

	/**
	  * tipo_venta
	  * @var enum('credito','contado') tipo de venta, contado o credito
	  */
	protected $tipo_venta;

	/**
	  * fecha
	  * @var timestamp fecha de venta
	  */
	protected $fecha;

	/**
	  * subtotal
	  * @var float subtotal de la venta
	  */
	protected $subtotal;

	/**
	  * iva
	  * @var float iva agregado por la venta
	  */
	protected $iva;

	/**
	  * id_sucursal
	  * @var int(11) sucursal de la venta
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * @var int(11) empleado que lo vendio
	  */
	protected $id_usuario;

	/**
	  * es llave primara 
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
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * @return enum('credito','contado')
	  */
	final public function getTipoVenta()
	{
		return $this->tipo_venta;
	}

	/**
	  * @param enum('credito','contado')
	  */
	final public function setTipoVenta( $tipo_venta )
	{
		$this->tipo_venta = $tipo_venta;
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
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * @param float
	  */
	final public function setSubtotal( $subtotal )
	{
		$this->subtotal = $subtotal;
	}

	/**
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * @param float
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
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
