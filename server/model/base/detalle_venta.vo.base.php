<?php
/* Value Object file for table DetalleVenta */

class DetalleVenta
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_venta = $data['id_venta'];
			$this->id_producto = $data['id_producto'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
		}
	}

	/**
	  * id_venta
	  * es llave primara 
	  * @var int(11) venta a que se referencia
	  */
	protected $id_venta;

	/**
	  * id_producto
	  * es llave primara 
	  * @var int(11) producto de la venta
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * @var float cantidad que se vendio
	  */
	protected $cantidad;

	/**
	  * precio
	  * @var float precio al que se vendio
	  */
	protected $precio;

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
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * @return float
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * @param float
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * @return float
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * @param float
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

}
