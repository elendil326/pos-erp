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
	  * @return null
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta
	  * 
	  * Set the <i>id_venta</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * getCliente
	  * 
	  * Get the <i>cliente</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getCliente()
	{
		return $this->cliente;
	}

	/**
	  * setCliente
	  * 
	  * Set the <i>cliente</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setCliente( $cliente )
	{
		$this->cliente = $cliente;
	}

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * setIdCliente
	  * 
	  * Set the <i>id_cliente</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getTipoVenta()
	{
		return $this->tipo_venta;
	}

	/**
	  * setTipoVenta
	  * 
	  * Set the <i>tipo_venta</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setTipoVenta( $tipo_venta )
	{
		$this->tipo_venta = $tipo_venta;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha
	  * 
	  * Set the <i>fecha</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * setSubtotal
	  * 
	  * Set the <i>subtotal</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setSubtotal( $subtotal )
	{
		$this->subtotal = $subtotal;
	}

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * setIva
	  * 
	  * Set the <i>iva</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
	}

	/**
	  * getSucursal
	  * 
	  * Get the <i>sucursal</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getSucursal()
	{
		return $this->sucursal;
	}

	/**
	  * setSucursal
	  * 
	  * Set the <i>sucursal</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setSucursal( $sucursal )
	{
		$this->sucursal = $sucursal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal
	  * 
	  * Set the <i>id_sucursal</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * setUsuario
	  * 
	  * Set the <i>usuario</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setUsuario( $usuario )
	{
		$this->usuario = $usuario;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this ViewVentas object.
	  * @return null
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario
	  * 
	  * Set the <i>id_usuario</i> property for this ViewVentas object.
	  * @param null
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

}
