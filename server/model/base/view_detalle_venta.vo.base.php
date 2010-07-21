<?php
/** Value Object file for View view_detalle_venta.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewDetalleVenta extends VO
{
	/**
	  * Constructor de ViewDetalleVenta
	  * 
	  * Para construir un objeto de tipo ViewDetalleVenta debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewDetalleVenta
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_venta = $data['id_venta'];
			$this->id_producto = $data['id_producto'];
			$this->denominacion = $data['denominacion'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
			$this->fecha = $data['fecha'];
			$this->tipo_venta = $data['tipo_venta'];
			$this->id_sucursal = $data['id_sucursal'];
		}
	}

	/**
	  * id_venta
	  * 
	  * @access protected
	  */
	protected $id_venta;

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
	  * tipo_venta
	  * 
	  * @access protected
	  */
	protected $tipo_venta;

	/**
	  * id_sucursal
	  * 
	  * @access protected
	  */
	protected $id_sucursal;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * getDenominacion
	  * 
	  * Get the <i>denominacion</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getDenominacion()
	{
		return $this->denominacion;
	}

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getTipoVenta()
	{
		return $this->tipo_venta;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewDetalleVenta object.
	  * @return unknown
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

}
