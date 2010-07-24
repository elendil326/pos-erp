<?php
/** Value Object file for View view_ventas.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewVentas extends VO
{
	/**
	  * Constructor de ViewVentas
	  * 
	  * Para construir un objeto de tipo ViewVentas debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewVentas
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_venta = $data['id_venta'];
			$this->cliente = $data['cliente'];
			$this->id_cliente = $data['id_cliente'];
			$this->tipo_venta = $data['tipo_venta'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->sucursal = $data['sucursal'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->usuario = $data['usuario'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_venta
	  * 
	  * @access protected
	  */
	protected $id_venta;

	/**
	  * cliente
	  * 
	  * @access protected
	  */
	protected $cliente;

	/**
	  * id_cliente
	  * 
	  * @access protected
	  */
	protected $id_cliente;

	/**
	  * tipo_venta
	  * 
	  * @access protected
	  */
	protected $tipo_venta;

	/**
	  * fecha
	  * 
	  * @access protected
	  */
	protected $fecha;

	/**
	  * subtotal
	  * 
	  * @access protected
	  */
	protected $subtotal;

	/**
	  * iva
	  * 
	  * @access protected
	  */
	protected $iva;

	/**
	  * sucursal
	  * 
	  * @access protected
	  */
	protected $sucursal;

	/**
	  * id_sucursal
	  * 
	  * @access protected
	  */
	protected $id_sucursal;

	/**
	  * usuario
	  * 
	  * @access protected
	  */
	protected $usuario;

	/**
	  * id_usuario
	  * 
	  * @access protected
	  */
	protected $id_usuario;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * getCliente
	  * 
	  * Get the <i>cliente</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getCliente()
	{
		return $this->cliente;
	}

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getTipoVenta()
	{
		return $this->tipo_venta;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * getSucursal
	  * 
	  * Get the <i>sucursal</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getSucursal()
	{
		return $this->sucursal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this ViewVentas object.
	  * @return unknown
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

}
