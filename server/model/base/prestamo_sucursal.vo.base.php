<?php
/** Value Object file for table prestamo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class PrestamoSucursal extends VO
{
	/**
	  * Constructor de PrestamoSucursal
	  * 
	  * Para construir un objeto de tipo PrestamoSucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PrestamoSucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_prestamo']) ){
				$this->id_prestamo = $data['id_prestamo'];
			}
			if( isset($data['prestamista']) ){
				$this->prestamista = $data['prestamista'];
			}
			if( isset($data['deudor']) ){
				$this->deudor = $data['deudor'];
			}
			if( isset($data['monto']) ){
				$this->monto = $data['monto'];
			}
			if( isset($data['saldo']) ){
				$this->saldo = $data['saldo'];
			}
			if( isset($data['liquidado']) ){
				$this->liquidado = $data['liquidado'];
			}
			if( isset($data['concepto']) ){
				$this->concepto = $data['concepto'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PrestamoSucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_prestamo" => $this->id_prestamo,
			"prestamista" => $this->prestamista,
			"deudor" => $this->deudor,
			"monto" => $this->monto,
			"saldo" => $this->saldo,
			"liquidado" => $this->liquidado,
			"concepto" => $this->concepto,
			"fecha" => $this->fecha
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_prestamo
	  * 
	  * El identificador de este prestamo<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_prestamo;

	/**
	  * prestamista
	  * 
	  * La sucursal que esta prestando<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $prestamista;

	/**
	  * deudor
	  * 
	  * La sucursal que esta recibiendo<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $deudor;

	/**
	  * monto
	  * 
	  * El monto prestado<br>
	  * @access protected
	  * @var float
	  */
	protected $monto;

	/**
	  * saldo
	  * 
	  * El saldo pendiente para liquidar<br>
	  * @access protected
	  * @var float
	  */
	protected $saldo;

	/**
	  * liquidado
	  * 
	  * Bandera para buscar rapidamente los prestamos que aun no estan saldados<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $liquidado;

	/**
	  * concepto
	  * 
	  * El concepto de este prestamo<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $concepto;

	/**
	  * fecha
	  * 
	  * fecha en la que se registro el gasto<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * getIdPrestamo
	  * 
	  * Get the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El identificador de este prestamo
	  * @return int(11)
	  */
	final public function getIdPrestamo()
	{
		return $this->id_prestamo;
	}

	/**
	  * setIdPrestamo( $id_prestamo )
	  * 
	  * Set the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El identificador de este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_prestamo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdPrestamo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPrestamo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPrestamo( $id_prestamo )
	{
		$this->id_prestamo = $id_prestamo;
	}

	/**
	  * getPrestamista
	  * 
	  * Get the <i>prestamista</i> property for this object. Donde <i>prestamista</i> es La sucursal que esta prestando
	  * @return int(11)
	  */
	final public function getPrestamista()
	{
		return $this->prestamista;
	}

	/**
	  * setPrestamista( $prestamista )
	  * 
	  * Set the <i>prestamista</i> property for this object. Donde <i>prestamista</i> es La sucursal que esta prestando.
	  * Una validacion basica se hara aqui para comprobar que <i>prestamista</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setPrestamista( $prestamista )
	{
		$this->prestamista = $prestamista;
	}

	/**
	  * getDeudor
	  * 
	  * Get the <i>deudor</i> property for this object. Donde <i>deudor</i> es La sucursal que esta recibiendo
	  * @return int(11)
	  */
	final public function getDeudor()
	{
		return $this->deudor;
	}

	/**
	  * setDeudor( $deudor )
	  * 
	  * Set the <i>deudor</i> property for this object. Donde <i>deudor</i> es La sucursal que esta recibiendo.
	  * Una validacion basica se hara aqui para comprobar que <i>deudor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setDeudor( $deudor )
	{
		$this->deudor = $deudor;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es El monto prestado
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es El monto prestado.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * getSaldo
	  * 
	  * Get the <i>saldo</i> property for this object. Donde <i>saldo</i> es El saldo pendiente para liquidar
	  * @return float
	  */
	final public function getSaldo()
	{
		return $this->saldo;
	}

	/**
	  * setSaldo( $saldo )
	  * 
	  * Set the <i>saldo</i> property for this object. Donde <i>saldo</i> es El saldo pendiente para liquidar.
	  * Una validacion basica se hara aqui para comprobar que <i>saldo</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setSaldo( $saldo )
	{
		$this->saldo = $saldo;
	}

	/**
	  * getLiquidado
	  * 
	  * Get the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es Bandera para buscar rapidamente los prestamos que aun no estan saldados
	  * @return tinyint(1)
	  */
	final public function getLiquidado()
	{
		return $this->liquidado;
	}

	/**
	  * setLiquidado( $liquidado )
	  * 
	  * Set the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es Bandera para buscar rapidamente los prestamos que aun no estan saldados.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setLiquidado( $liquidado )
	{
		$this->liquidado = $liquidado;
	}

	/**
	  * getConcepto
	  * 
	  * Get the <i>concepto</i> property for this object. Donde <i>concepto</i> es El concepto de este prestamo
	  * @return varchar(256)
	  */
	final public function getConcepto()
	{
		return $this->concepto;
	}

	/**
	  * setConcepto( $concepto )
	  * 
	  * Set the <i>concepto</i> property for this object. Donde <i>concepto</i> es El concepto de este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>concepto</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setConcepto( $concepto )
	{
		$this->concepto = $concepto;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en la que se registro el gasto
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en la que se registro el gasto.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

}
