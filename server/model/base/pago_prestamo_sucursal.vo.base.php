<?php
/** Value Object file for table pago_prestamo_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez
  * @access public
  * @package docs
  * 
  */

class PagoPrestamoSucursal extends VO
{
	/**
	  * Constructor de PagoPrestamoSucursal
	  * 
	  * Para construir un objeto de tipo PagoPrestamoSucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PagoPrestamoSucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_pago']) ){
				$this->id_pago = $data['id_pago'];
			}
			if( isset($data['id_prestamo']) ){
				$this->id_prestamo = $data['id_prestamo'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['monto']) ){
				$this->monto = $data['monto'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PagoPrestamoSucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_pago" => $this->id_pago,
			"id_prestamo" => $this->id_prestamo,
			"id_usuario" => $this->id_usuario,
			"fecha" => $this->fecha,
			"monto" => $this->monto
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_pago
	  * 
	  * El identificador de este pago<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_pago;

	/**
	  * id_prestamo
	  * 
	  * El id del prestamo al que pertenece este prestamo<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_prestamo;

	/**
	  * id_usuario
	  * 
	  * El usurio que recibe este dinero<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * fecha
	  * 
	  * La fecha cuando se realizo este pago<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * monto
	  * 
	  * El monto a abonar<br>
	  * @access protected
	  * @var float
	  */
	protected $monto;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es El identificador de este pago
	  * @return int(11)
	  */
	final public function getIdPago()
	{
		return $this->id_pago;
	}

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es El identificador de este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPago( $id_pago )
	{
		$this->id_pago = $id_pago;
	}

	/**
	  * getIdPrestamo
	  * 
	  * Get the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El id del prestamo al que pertenece este prestamo
	  * @return int(11)
	  */
	final public function getIdPrestamo()
	{
		return $this->id_prestamo;
	}

	/**
	  * setIdPrestamo( $id_prestamo )
	  * 
	  * Set the <i>id_prestamo</i> property for this object. Donde <i>id_prestamo</i> es El id del prestamo al que pertenece este prestamo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_prestamo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdPrestamo( $id_prestamo )
	{
		$this->id_prestamo = $id_prestamo;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es El usurio que recibe este dinero
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es El usurio que recibe este dinero.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es La fecha cuando se realizo este pago
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es La fecha cuando se realizo este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es El monto a abonar
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es El monto a abonar.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

}
