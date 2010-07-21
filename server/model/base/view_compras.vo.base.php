<?php
/** Value Object file for View view_compras.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewCompras extends VO
{
	/**
	  * Constructor de ViewCompras
	  * 
	  * Para construir un objeto de tipo ViewCompras debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewCompras
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_compra = $data['id_compra'];
			$this->proveedor = $data['proveedor'];
			$this->id_proveedor = $data['id_proveedor'];
			$this->tipo_compra = $data['tipo_compra'];
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
	  * id_compra
	  * 
	  * @access protected
	  */
	protected $id_compra;

	/**
	  * proveedor
	  * 
	  * @access protected
	  */
	protected $proveedor;

	/**
	  * id_proveedor
	  * 
	  * @access protected
	  */
	protected $id_proveedor;

	/**
	  * tipo_compra
	  * 
	  * @access protected
	  */
	protected $tipo_compra;

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
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * getProveedor
	  * 
	  * Get the <i>proveedor</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getProveedor()
	{
		return $this->proveedor;
	}

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * getTipoCompra
	  * 
	  * Get the <i>tipo_compra</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getTipoCompra()
	{
		return $this->tipo_compra;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * getSucursal
	  * 
	  * Get the <i>sucursal</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getSucursal()
	{
		return $this->sucursal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this ViewCompras object.
	  * @return unknown
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

}
