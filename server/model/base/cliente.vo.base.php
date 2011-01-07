<?php
/** Value Object file for table cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class Cliente extends VO
{
	/**
	  * Constructor de Cliente
	  * 
	  * Para construir un objeto de tipo Cliente debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Cliente
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_cliente']) ){
				$this->id_cliente = $data['id_cliente'];
			}
			if( isset($data['rfc']) ){
				$this->rfc = $data['rfc'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['direccion']) ){
				$this->direccion = $data['direccion'];
			}
			if( isset($data['ciudad']) ){
				$this->ciudad = $data['ciudad'];
			}
			if( isset($data['telefono']) ){
				$this->telefono = $data['telefono'];
			}
			if( isset($data['e_mail']) ){
				$this->e_mail = $data['e_mail'];
			}
			if( isset($data['limite_credito']) ){
				$this->limite_credito = $data['limite_credito'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['fecha_ingreso']) ){
				$this->fecha_ingreso = $data['fecha_ingreso'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Cliente en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_cliente" => $this->id_cliente,
			"rfc" => $this->rfc,
			"nombre" => $this->nombre,
			"direccion" => $this->direccion,
			"ciudad" => $this->ciudad,
			"telefono" => $this->telefono,
			"e_mail" => $this->e_mail,
			"limite_credito" => $this->limite_credito,
			"descuento" => $this->descuento,
			"activo" => $this->activo,
			"id_usuario" => $this->id_usuario,
			"id_sucursal" => $this->id_sucursal,
			"fecha_ingreso" => $this->fecha_ingreso
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_cliente
	  * 
	  * identificador del cliente<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cliente;

	/**
	  * rfc
	  * 
	  * rfc del cliente si es que tiene<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $rfc;

	/**
	  * nombre
	  * 
	  * nombre del cliente<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $nombre;

	/**
	  * direccion
	  * 
	  * domicilio del cliente calle, no, colonia<br>
	  * @access protected
	  * @var varchar(300)
	  */
	protected $direccion;

	/**
	  * ciudad
	  * 
	  * Ciudad de este cliente<br>
	  * @access protected
	  * @var varchar(55)
	  */
	protected $ciudad;

	/**
	  * telefono
	  * 
	  * Telefono del cliete<br>
	  * @access protected
	  * @var varchar(25)
	  */
	protected $telefono;

	/**
	  * e_mail
	  * 
	  * dias de credito para que pague el cliente<br>
	  * @access protected
	  * @var varchar(60)
	  */
	protected $e_mail;

	/**
	  * limite_credito
	  * 
	  * Limite de credito otorgado al cliente<br>
	  * @access protected
	  * @var float
	  */
	protected $limite_credito;

	/**
	  * descuento
	  * 
	  * Taza porcentual de descuento de 0.0 a 100.0<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * activo
	  * 
	  * Indica si la cuenta esta activada o desactivada<br>
	  * @access protected
	  * @var tinyint(2)
	  */
	protected $activo;

	/**
	  * id_usuario
	  * 
	  * Identificador del usuario que dio de alta a este cliente<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * id_sucursal
	  * 
	  * Identificador de la sucursal donde se dio de alta este cliente<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * fecha_ingreso
	  * 
	  * Fecha cuando este cliente se registro en una sucursal<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_ingreso;

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es identificador del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCliente( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCliente( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene
	  * @return varchar(20)
	  */
	final public function getRfc()
	{
		return $this->rfc;
	}

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del cliente si es que tiene.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setRfc( $rfc )
	{
		$this->rfc = $rfc;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del cliente
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es domicilio del cliente calle, no, colonia
	  * @return varchar(300)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es domicilio del cliente calle, no, colonia.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(300)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(300)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

	/**
	  * getCiudad
	  * 
	  * Get the <i>ciudad</i> property for this object. Donde <i>ciudad</i> es Ciudad de este cliente
	  * @return varchar(55)
	  */
	final public function getCiudad()
	{
		return $this->ciudad;
	}

	/**
	  * setCiudad( $ciudad )
	  * 
	  * Set the <i>ciudad</i> property for this object. Donde <i>ciudad</i> es Ciudad de este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>ciudad</i> es de tipo <i>varchar(55)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(55)
	  */
	final public function setCiudad( $ciudad )
	{
		$this->ciudad = $ciudad;
	}

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete
	  * @return varchar(25)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es Telefono del cliete.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(25)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(25)
	  */
	final public function setTelefono( $telefono )
	{
		$this->telefono = $telefono;
	}

	/**
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente
	  * @return varchar(60)
	  */
	final public function getEMail()
	{
		return $this->e_mail;
	}

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es dias de credito para que pague el cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(60)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(60)
	  */
	final public function setEMail( $e_mail )
	{
		$this->e_mail = $e_mail;
	}

	/**
	  * getLimiteCredito
	  * 
	  * Get the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente
	  * @return float
	  */
	final public function getLimiteCredito()
	{
		return $this->limite_credito;
	}

	/**
	  * setLimiteCredito( $limite_credito )
	  * 
	  * Set the <i>limite_credito</i> property for this object. Donde <i>limite_credito</i> es Limite de credito otorgado al cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>limite_credito</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setLimiteCredito( $limite_credito )
	{
		$this->limite_credito = $limite_credito;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0.0 a 100.0.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada
	  * @return tinyint(2)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Indica si la cuenta esta activada o desactivada.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(2)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(2)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Identificador del usuario que dio de alta a este cliente.
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
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de la sucursal donde se dio de alta este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal
	  * @return timestamp
	  */
	final public function getFechaIngreso()
	{
		return $this->fecha_ingreso;
	}

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha cuando este cliente se registro en una sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaIngreso( $fecha_ingreso )
	{
		$this->fecha_ingreso = $fecha_ingreso;
	}

}
