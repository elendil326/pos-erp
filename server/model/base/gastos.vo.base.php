<?php
/** Value Object file for table gastos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Gastos extends VO
{
	/**
	  * Constructor de Gastos
	  * 
	  * Para construir un objeto de tipo Gastos debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Gastos
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_gasto = $data['id_gasto'];
			$this->concepto = $data['concepto'];
			$this->monto = $data['monto'];
			$this->fecha = $data['fecha'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_gasto
	  * 
	  * id para identificar el gasto<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_gasto;

	/**
	  * concepto
	  * 
	  * concepto en lo que se gasto<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $concepto;

	/**
	  * monto
	  * 
	  * lo que costo este gasto<br>
	  * @access protected
	  * @var float
	  */
	protected $monto;

	/**
	  * fecha
	  * 
	  * fecha del gasto<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * id_sucursal
	  * 
	  * sucursal en la que se hizo el gasto<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * 
	  * usuario que registro el gasto<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * getIdGasto
	  * 
	  * Get the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto
	  * @return int(11)
	  */
	final public function getIdGasto()
	{
		return $this->id_gasto;
	}

	/**
	  * setIdGasto( $id_gasto )
	  * 
	  * Set the <i>id_gasto</i> property for this object. Donde <i>id_gasto</i> es id para identificar el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_gasto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdGasto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdGasto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdGasto( $id_gasto )
	{
		$this->id_gasto = $id_gasto;
	}

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto
	  * @return varchar(100)
	  */
	final public function getConcepto()
	{
		return $this->concepto;
	}

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es concepto en lo que se gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setConcepto( $concepto )
	{
		$this->concepto = $concepto;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es lo que costo este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en la que se hizo el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es usuario que registro el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

}
