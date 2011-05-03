<?php
/** Value Object file for table cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
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
			if( isset($data['razon_social']) ){
				$this->razon_social = $data['razon_social'];
			}
			if( isset($data['calle']) ){
				$this->calle = $data['calle'];
			}
			if( isset($data['numero_exterior']) ){
				$this->numero_exterior = $data['numero_exterior'];
			}
			if( isset($data['numero_interior']) ){
				$this->numero_interior = $data['numero_interior'];
			}
			if( isset($data['colonia']) ){
				$this->colonia = $data['colonia'];
			}
			if( isset($data['referencia']) ){
				$this->referencia = $data['referencia'];
			}
			if( isset($data['localidad']) ){
				$this->localidad = $data['localidad'];
			}
			if( isset($data['municipio']) ){
				$this->municipio = $data['municipio'];
			}
			if( isset($data['estado']) ){
				$this->estado = $data['estado'];
			}
			if( isset($data['pais']) ){
				$this->pais = $data['pais'];
			}
			if( isset($data['codigo_postal']) ){
				$this->codigo_postal = $data['codigo_postal'];
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
			"razon_social" => $this->razon_social,
			"calle" => $this->calle,
			"numero_exterior" => $this->numero_exterior,
			"numero_interior" => $this->numero_interior,
			"colonia" => $this->colonia,
			"referencia" => $this->referencia,
			"localidad" => $this->localidad,
			"municipio" => $this->municipio,
			"estado" => $this->estado,
			"pais" => $this->pais,
			"codigo_postal" => $this->codigo_postal,
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
	  * razon_social
	  * 
	  * razon social del cliente<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $razon_social;

	/**
	  * calle
	  * 
	  * calle del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(300)
	  */
	protected $calle;

	/**
	  * numero_exterior
	  * 
	  * numero exteriror del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $numero_exterior;

	/**
	  * numero_interior
	  * 
	  * numero interior del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $numero_interior;

	/**
	  * colonia
	  * 
	  * colonia del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $colonia;

	/**
	  * referencia
	  * 
	  * referencia del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $referencia;

	/**
	  * localidad
	  * 
	  * Localidad del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $localidad;

	/**
	  * municipio
	  * 
	  * Municipio de este cliente<br>
	  * @access protected
	  * @var varchar(55)
	  */
	protected $municipio;

	/**
	  * estado
	  * 
	  * Estado del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $estado;

	/**
	  * pais
	  * 
	  * Pais del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $pais;

	/**
	  * codigo_postal
	  * 
	  * Codigo postal del domicilio fiscal del cliente<br>
	  * @access protected
	  * @var varchar(15)
	  */
	protected $codigo_postal;

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
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente
	  * @return varchar(100)
	  */
	final public function getRazonSocial()
	{
		return $this->razon_social;
	}

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setRazonSocial( $razon_social )
	{
		$this->razon_social = $razon_social;
	}

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente
	  * @return varchar(300)
	  */
	final public function getCalle()
	{
		return $this->calle;
	}

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(300)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(300)
	  */
	final public function setCalle( $calle )
	{
		$this->calle = $calle;
	}

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente
	  * @return varchar(10)
	  */
	final public function getNumeroExterior()
	{
		return $this->numero_exterior;
	}

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es numero exteriror del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_exterior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	final public function setNumeroExterior( $numero_exterior )
	{
		$this->numero_exterior = $numero_exterior;
	}

	/**
	  * getNumeroInterior
	  * 
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente
	  * @return varchar(10)
	  */
	final public function getNumeroInterior()
	{
		return $this->numero_interior;
	}

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_interior</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	final public function setNumeroInterior( $numero_interior )
	{
		$this->numero_interior = $numero_interior;
	}

	/**
	  * getColonia
	  * 
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	final public function getColonia()
	{
		return $this->colonia;
	}

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setColonia( $colonia )
	{
		$this->colonia = $colonia;
	}

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente
	  * @return varchar(100)
	  */
	final public function getReferencia()
	{
		return $this->referencia;
	}

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setReferencia( $referencia )
	{
		$this->referencia = $referencia;
	}

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getLocalidad()
	{
		return $this->localidad;
	}

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es Localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setLocalidad( $localidad )
	{
		$this->localidad = $localidad;
	}

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente
	  * @return varchar(55)
	  */
	final public function getMunicipio()
	{
		return $this->municipio;
	}

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es Municipio de este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(55)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(55)
	  */
	final public function setMunicipio( $municipio )
	{
		$this->municipio = $municipio;
	}

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	final public function getEstado()
	{
		return $this->estado;
	}

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es Estado del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>estado</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setEstado( $estado )
	{
		$this->estado = $estado;
	}

	/**
	  * getPais
	  * 
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente
	  * @return varchar(50)
	  */
	final public function getPais()
	{
		return $this->pais;
	}

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es Pais del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>pais</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setPais( $pais )
	{
		$this->pais = $pais;
	}

	/**
	  * getCodigoPostal
	  * 
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente
	  * @return varchar(15)
	  */
	final public function getCodigoPostal()
	{
		return $this->codigo_postal;
	}

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es Codigo postal del domicilio fiscal del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_postal</i> es de tipo <i>varchar(15)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(15)
	  */
	final public function setCodigoPostal( $codigo_postal )
	{
		$this->codigo_postal = $codigo_postal;
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
