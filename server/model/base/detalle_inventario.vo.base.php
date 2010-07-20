<?php
/* Value Object file for table DetalleInventario */

class DetalleInventario
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_producto = $data['id_producto'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->precio_venta = $data['precio_venta'];
			$this->min = $data['min'];
			$this->existencias = $data['existencias'];
		}
	}

	/**
	  * id_producto
	  * es llave primara 
	  * @var int(11) id del producto al que se refiere
	  */
	protected $id_producto;

	/**
	  * id_sucursal
	  * es llave primara 
	  * @var int(11) id de la sucursal
	  */
	protected $id_sucursal;

	/**
	  * precio_venta
	  * @var float precio al que se vendera al publico
	  */
	protected $precio_venta;

	/**
	  * min
	  * @var float cantidad minima que debe de haber del producto en almacen de esta sucursal
	  */
	protected $min;

	/**
	  * existencias
	  * @var float cantidad de producto que hay actualmente en almacen de esta sucursal
	  */
	protected $existencias;

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
	  * es llave primara 
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
	  * @return float
	  */
	final public function getPrecioVenta()
	{
		return $this->precio_venta;
	}

	/**
	  * @param float
	  */
	final public function setPrecioVenta( $precio_venta )
	{
		$this->precio_venta = $precio_venta;
	}

	/**
	  * @return float
	  */
	final public function getMin()
	{
		return $this->min;
	}

	/**
	  * @param float
	  */
	final public function setMin( $min )
	{
		$this->min = $min;
	}

	/**
	  * @return float
	  */
	final public function getExistencias()
	{
		return $this->existencias;
	}

	/**
	  * @param float
	  */
	final public function setExistencias( $existencias )
	{
		$this->existencias = $existencias;
	}

}
