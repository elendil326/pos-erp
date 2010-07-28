<?php
/** Value Object file for View view_detalle_compra.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewDetalleCompra extends VO
{
	/**
	  * Constructor de ViewDetalleCompra
	  * 
	  * Para construir un objeto de tipo ViewDetalleCompra debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewDetalleCompra
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_compra = $data['id_compra'];
			$this->id_producto = $data['id_producto'];
			$this->denominacion = $data['denominacion'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
			$this->fecha = $data['fecha'];
			$this->tipo_compra = $data['tipo_compra'];
			$this->id_sucursal = $data['id_sucursal'];
		}
	}

	/**
	  * id_compra
	  * 
	  * @access protected
	  */
	protected $id_compra;

	/**
	  * id_producto
	  * 
	  * @access protected
	  */
	protected $id_producto;

	/**
	  * denominacion
	  * 
	  * @access protected
	  */
	protected $denominacion;

	/**
	  * cantidad
	  * 
	  * @access protected
	  */
	protected $cantidad;

	/**
	  * precio
	  * 
	  * @access protected
	  */
	protected $precio;

	/**
	  * fecha
	  * 
	  * @access protected
	  */
	protected $fecha;

	/**
	  * tipo_compra
	  * 
	  * @access protected
	  */
	protected $tipo_compra;

	/**
	  * id_sucursal
	  * 
	  * @access protected
	  */
	protected $id_sucursal;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra
	  * 
	  * Set the <i>id_compra</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto
	  * 
	  * Set the <i>id_producto</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getDenominacion
	  * 
	  * Get the <i>denominacion</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getDenominacion()
	{
		return $this->denominacion;
	}

	/**
	  * setDenominacion
	  * 
	  * Set the <i>denominacion</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setDenominacion( $denominacion )
	{
		$this->denominacion = $denominacion;
	}

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad
	  * 
	  * Set the <i>cantidad</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio
	  * 
	  * Set the <i>precio</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha
	  * 
	  * Set the <i>fecha</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getTipoCompra
	  * 
	  * Get the <i>tipo_compra</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getTipoCompra()
	{
		return $this->tipo_compra;
	}

	/**
	  * setTipoCompra
	  * 
	  * Set the <i>tipo_compra</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setTipoCompra( $tipo_compra )
	{
		$this->tipo_compra = $tipo_compra;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewDetalleCompra object.
	  * @return null
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal
	  * 
	  * Set the <i>id_sucursal</i> property for this ViewDetalleCompra object.
	  * @param null
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

}
