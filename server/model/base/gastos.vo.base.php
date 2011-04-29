<?php
/** Value Object file for table gastos.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez
  * @access public
  * @package docs
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
			if( isset($data['id_gasto']) ){
				$this->id_gasto = $data['id_gasto'];
			}
			if( isset($data['folio']) ){
				$this->folio = $data['folio'];
			}
			if( isset($data['concepto']) ){
				$this->concepto = $data['concepto'];
			}
			if( isset($data['monto']) ){
				$this->monto = $data['monto'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['fecha_ingreso']) ){
				$this->fecha_ingreso = $data['fecha_ingreso'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['nota']) ){
				$this->nota = $data['nota'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Gastos en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_gasto" => $this->id_gasto,
			"folio" => $this->folio,
			"concepto" => $this->concepto,
			"monto" => $this->monto,
			"fecha" => $this->fecha,
			"fecha_ingreso" => $this->fecha_ingreso,
			"id_sucursal" => $this->id_sucursal,
			"id_usuario" => $this->id_usuario,
			"nota" => $this->nota
		); 
	return json_encode($vec); 
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
	  * folio
	  * 
	  * El folio de la factura para este gasto<br>
	  * @access protected
	  * @var varchar(22)
	  */
	protected $folio;

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
	  * fecha_ingreso
	  * 
	  * Fecha que selecciono el empleado en el sistema<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_ingreso;

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
	  * nota
	  * 
	  * nota adicional para complementar la descripcion del gasto<br>
	  * @access protected
	  * @var varchar(512)
	  */
	protected $nota;

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
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto
	  * @return varchar(22)
	  */
	final public function getFolio()
	{
		return $this->folio;
	}

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es El folio de la factura para este gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(22)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(22)
	  */
	final public function setFolio( $folio )
	{
		$this->folio = $folio;
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
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema
	  * @return timestamp
	  */
	final public function getFechaIngreso()
	{
		return $this->fecha_ingreso;
	}

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha que selecciono el empleado en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaIngreso( $fecha_ingreso )
	{
		$this->fecha_ingreso = $fecha_ingreso;
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

	/**
	  * getNota
	  * 
	  * Get the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto
	  * @return varchar(512)
	  */
	final public function getNota()
	{
		return $this->nota;
	}

	/**
	  * setNota( $nota )
	  * 
	  * Set the <i>nota</i> property for this object. Donde <i>nota</i> es nota adicional para complementar la descripcion del gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>nota</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	final public function setNota( $nota )
	{
		$this->nota = $nota;
	}

}
