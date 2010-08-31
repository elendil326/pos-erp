<?php
/** Value Object file for View view_gastos.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewGastos extends VO
{
	/**
	  * Constructor de ViewGastos
	  * 
	  * Para construir un objeto de tipo ViewGastos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewGastos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_gasto = $data['id_gasto'];
			$this->concepto = $data['concepto'];
			$this->monto = $data['monto'];
			$this->fecha = $data['fecha'];
			$this->sucursal = $data['sucursal'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->usuario = $data['usuario'];
		}
	}

	/**
	  * id_gasto
	  * 
	  * @access protected
	  */
	protected $id_gasto;

	/**
	  * concepto
	  * 
	  * @access protected
	  */
	protected $concepto;

	/**
	  * monto
	  * 
	  * @access protected
	  */
	protected $monto;

	/**
	  * fecha
	  * 
	  * @access protected
	  */
	protected $fecha;

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
	  * getIdGasto
	  * 
	  * Get the <i>id_gasto</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getIdGasto()
	{
		return $this->id_gasto;
	}

	/**
	  * setIdGasto
	  * 
	  * Set the <i>id_gasto</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setIdGasto( $id_gasto )
	{
		$this->id_gasto = $id_gasto;
	}

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getConcepto()
	{
		return $this->concepto;
	}

	/**
	  * setConcepto
	  * 
	  * Set the <i>concepto</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setConcepto( $concepto )
	{
		$this->concepto = $concepto;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto
	  * 
	  * Set the <i>monto</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha
	  * 
	  * Set the <i>fecha</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getSucursal
	  * 
	  * Get the <i>sucursal</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getSucursal()
	{
		return $this->sucursal;
	}

	/**
	  * setSucursal
	  * 
	  * Set the <i>sucursal</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setSucursal( $sucursal )
	{
		$this->sucursal = $sucursal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal
	  * 
	  * Set the <i>id_sucursal</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this ViewGastos object.
	  * @return null
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * setUsuario
	  * 
	  * Set the <i>usuario</i> property for this ViewGastos object.
	  * @param null
	  */
	final public function setUsuario( $usuario )
	{
		$this->usuario = $usuario;
	}

}
