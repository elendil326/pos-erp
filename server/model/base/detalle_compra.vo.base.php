<?php
/* Value Object file for table DetalleCompra */

class DetalleCompra
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_compra = $data['id_compra'];
			$this->id_producto = $data['id_producto'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
		}
	}

	/**
	  * id_compra
	  * es llave primara 
	  * @var int(11) id de la compra
	  */
	protected $id_compra;

	/**
	  * id_producto
	  * es llave primara 
	  * @var int(11) id del producto
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * @var float cantidad comprada
	  */
	protected $cantidad;

	/**
	  * precio
	  * @var float costo de compra
	  */
	protected $precio;

	/**
	  * es llave primara 
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
