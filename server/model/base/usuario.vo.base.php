<?php
/** Value Object file for table usuario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Usuario extends VO
{
	/**
	  * Constructor de Usuario
	  * 
	  * Para construir un objeto de tipo Usuario debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Usuario
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['RFC']) ){
				$this->RFC = $data['RFC'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['contrasena']) ){
				$this->contrasena = $data['contrasena'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['finger_token']) ){
				$this->finger_token = $data['finger_token'];
			}
			if( isset($data['salario']) ){
				$this->salario = $data['salario'];
			}
			if( isset($data['direccion']) ){
				$this->direccion = $data['direccion'];
			}
			if( isset($data['telefono']) ){
				$this->telefono = $data['telefono'];
			}
			if( isset($data['ultimo_acceso']) ){
				$this->ultimo_acceso = $data['ultimo_acceso'];
			}
			if( isset($data['online']) ){
				$this->online = $data['online'];
			}
			if( isset($data['fecha_inicio']) ){
				$this->fecha_inicio = $data['fecha_inicio'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Usuario en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_usuario" => $this->id_usuario,
			"RFC" => $this->RFC,
			"nombre" => $this->nombre,
			"contrasena" => $this->contrasena,
			"id_sucursal" => $this->id_sucursal,
			"activo" => $this->activo,
			"finger_token" => $this->finger_token,
			"salario" => $this->salario,
			"direccion" => $this->direccion,
			"telefono" => $this->telefono,
			"ultimo_acceso" => $this->ultimo_acceso,
			"online" => $this->online,
			"fecha_inicio" => $this->fecha_inicio
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_usuario
	  * 
	  * identificador del usuario<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * RFC
	  * 
	  * RFC de este usuario<br>
	  * @access protected
	  * @var varchar(40)
	  */
	protected $RFC;

	/**
	  * nombre
	  * 
	  * nombre del empleado<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $nombre;

	/**
	  * contrasena
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $contrasena;

	/**
	  * id_sucursal
	  * 
	  * Id de la sucursal a que pertenece<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * activo
	  * 
	  * Guarda el estado de la cuenta del usuario<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activo;

	/**
	  * finger_token
	  * 
	  * Una cadena que sera comparada con el token que mande el scanner de huella digital<br>
	  * @access protected
	  * @var varchar(1024)
	  */
	protected $finger_token;

	/**
	  * salario
	  * 
	  * Salario actual<br>
	  * @access protected
	  * @var float
	  */
	protected $salario;

	/**
	  * direccion
	  * 
	  * Direccion del empleado<br>
	  * @access protected
	  * @var varchar(512)
	  */
	protected $direccion;

	/**
	  * telefono
	  * 
	  * Telefono del empleado<br>
	  * @access protected
	  * @var varchar(16)
	  */
	protected $telefono;

	/**
	  * ultimo_acceso
	  * 
	  * la ultima vez que este usuario hizo una peticion al sistema<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $ultimo_acceso;

	/**
	  * online
	  * 
	  * esta online justo ahora ?<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $online;

	/**
	  * fecha_inicio
	  * 
	  * Fecha cuando este usuario comenzo a laborar<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_inicio;

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es identificador del usuario
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es identificador del usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdUsuario( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUsuario( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getRFC
	  * 
	  * Get the <i>RFC</i> property for this object. Donde <i>RFC</i> es RFC de este usuario
	  * @return varchar(40)
	  */
	final public function getRFC()
	{
		return $this->RFC;
	}

	/**
	  * setRFC( $RFC )
	  * 
	  * Set the <i>RFC</i> property for this object. Donde <i>RFC</i> es RFC de este usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>RFC</i> es de tipo <i>varchar(40)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(40)
	  */
	final public function setRFC( $RFC )
	{
		$this->RFC = $RFC;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del empleado
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getContrasena
	  * 
	  * Get the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	final public function getContrasena()
	{
		return $this->contrasena;
	}

	/**
	  * setContrasena( $contrasena )
	  * 
	  * Set the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>contrasena</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setContrasena( $contrasena )
	{
		$this->contrasena = $contrasena;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Id de la sucursal a que pertenece
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Id de la sucursal a que pertenece.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Guarda el estado de la cuenta del usuario
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Guarda el estado de la cuenta del usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getFingerToken
	  * 
	  * Get the <i>finger_token</i> property for this object. Donde <i>finger_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital
	  * @return varchar(1024)
	  */
	final public function getFingerToken()
	{
		return $this->finger_token;
	}

	/**
	  * setFingerToken( $finger_token )
	  * 
	  * Set the <i>finger_token</i> property for this object. Donde <i>finger_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital.
	  * Una validacion basica se hara aqui para comprobar que <i>finger_token</i> es de tipo <i>varchar(1024)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(1024)
	  */
	final public function setFingerToken( $finger_token )
	{
		$this->finger_token = $finger_token;
	}

	/**
	  * getSalario
	  * 
	  * Get the <i>salario</i> property for this object. Donde <i>salario</i> es Salario actual
	  * @return float
	  */
	final public function getSalario()
	{
		return $this->salario;
	}

	/**
	  * setSalario( $salario )
	  * 
	  * Set the <i>salario</i> property for this object. Donde <i>salario</i> es Salario actual.
	  * Una validacion basica se hara aqui para comprobar que <i>salario</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setSalario( $salario )
	{
		$this->salario = $salario;
	}

	/**
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es Direccion del empleado
	  * @return varchar(512)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es Direccion del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del empleado
	  * @return varchar(16)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del empleado.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	final public function setTelefono( $telefono )
	{
		$this->telefono = $telefono;
	}

	/**
	  * getUltimoAcceso
	  * 
	  * Get the <i>ultimo_acceso</i> property for this object. Donde <i>ultimo_acceso</i> es la ultima vez que este usuario hizo una peticion al sistema
	  * @return timestamp
	  */
	final public function getUltimoAcceso()
	{
		return $this->ultimo_acceso;
	}

	/**
	  * setUltimoAcceso( $ultimo_acceso )
	  * 
	  * Set the <i>ultimo_acceso</i> property for this object. Donde <i>ultimo_acceso</i> es la ultima vez que este usuario hizo una peticion al sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>ultimo_acceso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setUltimoAcceso( $ultimo_acceso )
	{
		$this->ultimo_acceso = $ultimo_acceso;
	}

	/**
	  * getOnline
	  * 
	  * Get the <i>online</i> property for this object. Donde <i>online</i> es esta online justo ahora ?
	  * @return tinyint(1)
	  */
	final public function getOnline()
	{
		return $this->online;
	}

	/**
	  * setOnline( $online )
	  * 
	  * Set the <i>online</i> property for this object. Donde <i>online</i> es esta online justo ahora ?.
	  * Una validacion basica se hara aqui para comprobar que <i>online</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setOnline( $online )
	{
		$this->online = $online;
	}

	/**
	  * getFechaInicio
	  * 
	  * Get the <i>fecha_inicio</i> property for this object. Donde <i>fecha_inicio</i> es Fecha cuando este usuario comenzo a laborar
	  * @return timestamp
	  */
	final public function getFechaInicio()
	{
		return $this->fecha_inicio;
	}

	/**
	  * setFechaInicio( $fecha_inicio )
	  * 
	  * Set the <i>fecha_inicio</i> property for this object. Donde <i>fecha_inicio</i> es Fecha cuando este usuario comenzo a laborar.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_inicio</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaInicio( $fecha_inicio )
	{
		$this->fecha_inicio = $fecha_inicio;
	}

}
