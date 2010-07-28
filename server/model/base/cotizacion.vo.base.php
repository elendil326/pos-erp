<?php
/** Value Object file for table cotizacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Cotizacion extends VO
{
	/**
	  * Constructor de Cotizacion
	  * 
	  * Para construir un objeto de tipo Cotizacion debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Cotizacion
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_cotizacion = $data['id_cotizacion'];
			$this->id_cliente = $data['id_cliente'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Cotizacion en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_cotizacion" => $this->id_cotizacion,
		"id_cliente" => $this->id_cliente,
		"fecha" => $this->fecha,
		"subtotal" => $this->subtotal,
		"iva" => $this->iva,
		"id_sucursal" => $this->id_sucursal,
		"id_usuario" => $this->id_usuario
		)); 
	return json_encode($vec, true); 
	}
	
	/**
	  * id_cotizacion
	  * 
	  * id de la cotizacion<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cotizacion;

	/**
	  * id_cliente
	  * 
	  * id del cliente<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cliente;

	/**
	  * fecha
	  * 
	  * fecha de cotizacion<br>
	  * @access protected
	  * @var date
	  */
	protected $fecha;

	/**
	  * subtotal
	  * 
	  * subtotal de la cotizacion<br>
	  * @access protected
	  * @var float
	  */
	protected $subtotal;

	/**
	  * iva
	  * 
	  * iva sobre el subtotal<br>
	  * @access protected
	  * @var float
	  */
	protected $iva;

	/**
	  * id_sucursal
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * getIdCotizacion
	  * 
	  * Get the <i>id_cotizacion</i> property for this object. Donde <i>id_cotizacion</i> es id de la cotizacion
	  * @return int(11)
	  */
	final public function getIdCotizacion()
	{
		return $this->id_cotizacion;
	}

	/**
	  * setIdCotizacion( $id_cotizacion )
	  * 
	  * Set the <i>id_cotizacion</i> property for this object. Donde <i>id_cotizacion</i> es id de la cotizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cotizacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCotizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCotizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCotizacion( $id_cotizacion )
	{
		$this->id_cotizacion = $id_cotizacion;
	}

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es id del cliente
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es id del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cotizacion
	  * @return date
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cotizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la cotizacion
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la cotizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setSubtotal( $subtotal )
	{
		$this->subtotal = $subtotal;
	}

	/**
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva sobre el subtotal
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva sobre el subtotal.
	  * Una validacion basica se hara aqui para comprobar que <i>iva</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Campo no documentado.
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
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Campo no documentado
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

}
