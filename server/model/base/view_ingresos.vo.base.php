<?php
/** Value Object file for View view_ingresos.
  * 
  * VO objects for views does not have any behaviour except for retrieval of its own data (accessors).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ViewIngresos extends VO
{
	/**
	  * Constructor de ViewIngresos
	  * 
	  * Para construir un objeto de tipo ViewIngresos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ViewIngresos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_ingreso = $data['id_ingreso'];
			$this->concepto = $data['concepto'];
			$this->monto = $data['monto'];
			$this->fecha = $data['fecha'];
			$this->sucursal = $data['sucursal'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->usuario = $data['usuario'];
		}
	}

	/**
	  * id_ingreso
	  * 
	  * @access protected
	  */
	protected $id_ingreso;

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
	  * getIdIngreso
	  * 
	  * Get the <i>id_ingreso</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getIdIngreso()
	{
		return $this->id_ingreso;
	}

	/**
	  * setIdIngreso
	  * 
	  * Set the <i>id_ingreso</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setIdIngreso( $id_ingreso )
	{
		$this->id_ingreso = $id_ingreso;
	}

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getConcepto()
	{
		return $this->concepto;
	}

	/**
	  * setConcepto
	  * 
	  * Set the <i>concepto</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setConcepto( $concepto )
	{
		$this->concepto = $concepto;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto
	  * 
	  * Set the <i>monto</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha
	  * 
	  * Set the <i>fecha</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getSucursal
	  * 
	  * Get the <i>sucursal</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getSucursal()
	{
		return $this->sucursal;
	}

	/**
	  * setSucursal
	  * 
	  * Set the <i>sucursal</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setSucursal( $sucursal )
	{
		$this->sucursal = $sucursal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal
	  * 
	  * Set the <i>id_sucursal</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this ViewIngresos object.
	  * @return null
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * setUsuario
	  * 
	  * Set the <i>usuario</i> property for this ViewIngresos object.
	  * @param null
	  */
	final public function setUsuario( $usuario )
	{
		$this->usuario = $usuario;
	}

}
