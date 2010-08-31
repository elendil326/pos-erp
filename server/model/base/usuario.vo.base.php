<?php
/** Value Object file for table usuario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
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
			$this->id_usuario = $data['id_usuario'];
			$this->nombre = $data['nombre'];
			$this->usuario = $data['usuario'];
			$this->contrasena = $data['contrasena'];
			$this->figer_token = $data['figer_token'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->last_check = $data['last_check'];
			$this->activo = $data['activo'];
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
		$vec = array();
		array_push($vec, array( 
		"id_usuario" => $this->id_usuario,
		"nombre" => $this->nombre,
		"usuario" => $this->usuario,
		"contrasena" => $this->contrasena,
		"figer_token" => $this->figer_token,
		"id_sucursal" => $this->id_sucursal,
		"last_check" => $this->last_check,
		"activo" => $this->activo
		)); 
	return json_encode($vec, true); 
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
	  * nombre
	  * 
	  * nombre del empleado<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $nombre;

	/**
	  * usuario
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $usuario;

	/**
	  * contrasena
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $contrasena;

	/**
	  * figer_token
	  * 
	  * Una cadena que sera comparada con el token que mande el scanner de huella digital<br>
	  * @access protected
	  * @var varchar(1024)
	  */
	protected $figer_token;

	/**
	  * id_sucursal
	  * 
	  * Id de la sucursal a que pertenece<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * last_check
	  * 
	  * Ultima vez que este usuario hizo una peticion al servidor<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $last_check;

	/**
	  * activo
	  * 
	  * Indica si la cuenta esta activada o desactivada<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activo;

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
	  * getUsuario
	  * 
	  * Get the <i>usuario</i> property for this object. Donde <i>usuario</i> es Campo no documentado
	  * @return varchar(50)
	  */
	final public function getUsuario()
	{
		return $this->usuario;
	}

	/**
	  * setUsuario( $usuario )
	  * 
	  * Set the <i>usuario</i> property for this object. Donde <i>usuario</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>usuario</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setUsuario( $usuario )
	{
		$this->usuario = $usuario;
	}

	/**
	  * getContrasena
	  * 
	  * Get the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es Campo no documentado
	  * @return varchar(128)
	  */
	final public function getContrasena()
	{
		return $this->contrasena;
	}

	/**
	  * setContrasena( $contrasena )
	  * 
	  * Set the <i>contrasena</i> property for this object. Donde <i>contrasena</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>contrasena</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setContrasena( $contrasena )
	{
		$this->contrasena = $contrasena;
	}

	/**
	  * getFigerToken
	  * 
	  * Get the <i>figer_token</i> property for this object. Donde <i>figer_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital
	  * @return varchar(1024)
	  */
	final public function getFigerToken()
	{
		return $this->figer_token;
	}

	/**
	  * setFigerToken( $figer_token )
	  * 
	  * Set the <i>figer_token</i> property for this object. Donde <i>figer_token</i> es Una cadena que sera comparada con el token que mande el scanner de huella digital.
	  * Una validacion basica se hara aqui para comprobar que <i>figer_token</i> es de tipo <i>varchar(1024)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(1024)
	  */
	final public function setFigerToken( $figer_token )
	{
		$this->figer_token = $figer_token;
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
	  * getLastCheck
	  * 
	  * Get the <i>last_check</i> property for this object. Donde <i>last_check</i> es Ultima vez que este usuario hizo una peticion al servidor
	  * @return timestamp
	  */
	final public function getLastCheck()
	{
		return $this->last_check;
	}

	/**
	  * setLastCheck( $last_check )
	  * 
	  * Set the <i>last_check</i> property for this object. Donde <i>last_check</i> es Ultima vez que este usuario hizo una peticion al servidor.
	  * Una validacion basica se hara aqui para comprobar que <i>last_check</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setLastCheck( $last_check )
	{
		$this->last_check = $last_check;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

}
