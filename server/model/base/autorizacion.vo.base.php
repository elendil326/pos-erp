<?php
/** Value Object file for table autorizacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class Autorizacion extends VO
{
	/**
	  * Constructor de Autorizacion
	  * 
	  * Para construir un objeto de tipo Autorizacion debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Autorizacion
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_autorizacion']) ){
				$this->id_autorizacion = $data['id_autorizacion'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['fecha_peticion']) ){
				$this->fecha_peticion = $data['fecha_peticion'];
			}
			if( isset($data['fecha_respuesta']) ){
				$this->fecha_respuesta = $data['fecha_respuesta'];
			}
			if( isset($data['estado']) ){
				$this->estado = $data['estado'];
			}
			if( isset($data['parametros']) ){
				$this->parametros = $data['parametros'];
			}
			if( isset($data['tipo']) ){
				$this->tipo = $data['tipo'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Autorizacion en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_autorizacion" => $this->id_autorizacion,
			"id_usuario" => $this->id_usuario,
			"id_sucursal" => $this->id_sucursal,
			"fecha_peticion" => $this->fecha_peticion,
			"fecha_respuesta" => $this->fecha_respuesta,
			"estado" => $this->estado,
			"parametros" => $this->parametros,
			"tipo" => $this->tipo
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_autorizacion
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_autorizacion;

	/**
	  * id_usuario
	  * 
	  * Usuario que solicito esta autorizacion<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * id_sucursal
	  * 
	  * Sucursal de donde proviene esta autorizacion<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * fecha_peticion
	  * 
	  * Fecha cuando se genero esta autorizacion<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_peticion;

	/**
	  * fecha_respuesta
	  * 
	  * Fecha de cuando se resolvio esta aclaracion<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_respuesta;

	/**
	  * estado
	  * 
	  * Estado actual de esta aclaracion<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $estado;

	/**
	  * parametros
	  * 
	  * Parametros en formato JSON que describen esta autorizacion<br>
	  * @access protected
	  * @var varchar(2048)
	  */
	protected $parametros;

	/**
	  * tipo
	  * 
	  * El tipo de autorizacion<br>
	  * @access protected
	  * @var enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	protected $tipo;

	/**
	  * getIdAutorizacion
	  * 
	  * Get the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdAutorizacion()
	{
		return $this->id_autorizacion;
	}

	/**
	  * setIdAutorizacion( $id_autorizacion )
	  * 
	  * Set the <i>id_autorizacion</i> property for this object. Donde <i>id_autorizacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_autorizacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdAutorizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdAutorizacion( $id_autorizacion )
	{
		$this->id_autorizacion = $id_autorizacion;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario que solicito esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Sucursal de donde proviene esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getFechaPeticion
	  * 
	  * Get the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion
	  * @return timestamp
	  */
	final public function getFechaPeticion()
	{
		return $this->fecha_peticion;
	}

	/**
	  * setFechaPeticion( $fecha_peticion )
	  * 
	  * Set the <i>fecha_peticion</i> property for this object. Donde <i>fecha_peticion</i> es Fecha cuando se genero esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_peticion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaPeticion( $fecha_peticion )
	{
		$this->fecha_peticion = $fecha_peticion;
	}

	/**
	  * getFechaRespuesta
	  * 
	  * Get the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion
	  * @return timestamp
	  */
	final public function getFechaRespuesta()
	{
		return $this->fecha_respuesta;
	}

	/**
	  * setFechaRespuesta( $fecha_respuesta )
	  * 
	  * Set the <i>fecha_respuesta</i> property for this object. Donde <i>fecha_respuesta</i> es Fecha de cuando se resolvio esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_respuesta</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaRespuesta( $fecha_respuesta )
	{
		$this->fecha_respuesta = $fecha_respuesta;
	}

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion
	  * @return int(11)
	  */
	final public function getEstado()
	{
		return $this->estado;
	}

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado actual de esta aclaracion.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setEstado( $estado )
	{
		$this->estado = $estado;
	}

	/**
	  * getParametros
	  * 
	  * Get the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion
	  * @return varchar(2048)
	  */
	final public function getParametros()
	{
		return $this->parametros;
	}

	/**
	  * setParametros( $parametros )
	  * 
	  * Set the <i>parametros</i> property for this object. Donde <i>parametros</i> es Parametros en formato JSON que describen esta autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>parametros</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	final public function setParametros( $parametros )
	{
		$this->parametros = $parametros;
	}

	/**
	  * getTipo
	  * 
	  * Get the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion
	  * @return enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	final public function getTipo()
	{
		return $this->tipo;
	}

	/**
	  * setTipo( $tipo )
	  * 
	  * Set the <i>tipo</i> property for this object. Donde <i>tipo</i> es El tipo de autorizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo</i> es de tipo <i>enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('envioDeProductosASucursal','solicitudDeProductos','solicitudDeMerma','solicitudDeCambioPrecio','solicitudDeDevolucion','solicitudDeCambioLimiteDeCredito','solicitudDeGasto')
	  */
	final public function setTipo( $tipo )
	{
		$this->tipo = $tipo;
	}

}
