<?php
/* Value Object file for table DetalleCotizacion */

class DetalleCotizacion
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_cotizacion = $data['id_cotizacion'];
			$this->id_producto = $data['id_producto'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
		}
	}

	/**
	  * id_cotizacion
	  * es llave primara 
	  * @var int(11) id de la cotizacion
	  */
	protected $id_cotizacion;

	/**
	  * id_producto
	  * es llave primara 
	  * @var int(11) id del producto
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * @var float cantidad cotizado
	  */
	protected $cantidad;

	/**
	  * precio
	  * @var float precio al que cotizo el producto
	  */
	protected $precio;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdCotizacion()
	{
		return $this->id_cotizacion;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCotizacion( $id_cotizacion )
	{
		$this->id_cotizacion = $id_cotizacion;
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
