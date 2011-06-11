<?php
/** Value Object file for table sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Sucursal extends VO
{
	/**
	  * Constructor de Sucursal
	  * 
	  * Para construir un objeto de tipo Sucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Sucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['gerente']) ){
				$this->gerente = $data['gerente'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['razon_social']) ){
				$this->razon_social = $data['razon_social'];
			}
			if( isset($data['rfc']) ){
				$this->rfc = $data['rfc'];
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
			if( isset($data['localidad']) ){
				$this->localidad = $data['localidad'];
			}
			if( isset($data['referencia']) ){
				$this->referencia = $data['referencia'];
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
			if( isset($data['token']) ){
				$this->token = $data['token'];
			}
			if( isset($data['letras_factura']) ){
				$this->letras_factura = $data['letras_factura'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['fecha_apertura']) ){
				$this->fecha_apertura = $data['fecha_apertura'];
			}
			if( isset($data['saldo_a_favor']) ){
				$this->saldo_a_favor = $data['saldo_a_favor'];
			}
			if( isset($data['current_isp']) ){
				$this->current_isp = $data['current_isp'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Sucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_sucursal" => $this->id_sucursal,
			"gerente" => $this->gerente,
			"descripcion" => $this->descripcion,
			"razon_social" => $this->razon_social,
			"rfc" => $this->rfc,
			"calle" => $this->calle,
			"numero_exterior" => $this->numero_exterior,
			"numero_interior" => $this->numero_interior,
			"colonia" => $this->colonia,
			"localidad" => $this->localidad,
			"referencia" => $this->referencia,
			"municipio" => $this->municipio,
			"estado" => $this->estado,
			"pais" => $this->pais,
			"codigo_postal" => $this->codigo_postal,
			"telefono" => $this->telefono,
			"token" => $this->token,
			"letras_factura" => $this->letras_factura,
			"activo" => $this->activo,
			"fecha_apertura" => $this->fecha_apertura,
			"saldo_a_favor" => $this->saldo_a_favor,
			"current_isp" => $this->current_isp
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_sucursal
	  * 
	  * Identificador de cada sucursal<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * gerente
	  * 
	  * Gerente de esta sucursal<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $gerente;

	/**
	  * descripcion
	  * 
	  * nombre o descripcion de sucursal<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $descripcion;

	/**
	  * razon_social
	  * 
	  * razon social de la sucursal<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $razon_social;

	/**
	  * rfc
	  * 
	  * El RFC de la sucursal<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $rfc;

	/**
	  * calle
	  * 
	  * calle del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $calle;

	/**
	  * numero_exterior
	  * 
	  * nuemro exterior del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $numero_exterior;

	/**
	  * numero_interior
	  * 
	  * numero interior del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $numero_interior;

	/**
	  * colonia
	  * 
	  * colonia del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $colonia;

	/**
	  * localidad
	  * 
	  * localidad del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $localidad;

	/**
	  * referencia
	  * 
	  * referencia del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(200)
	  */
	protected $referencia;

	/**
	  * municipio
	  * 
	  * municipio del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $municipio;

	/**
	  * estado
	  * 
	  * estado del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $estado;

	/**
	  * pais
	  * 
	  * pais del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(50)
	  */
	protected $pais;

	/**
	  * codigo_postal
	  * 
	  * codigo postal del domicilio fiscal<br>
	  * @access protected
	  * @var varchar(15)
	  */
	protected $codigo_postal;

	/**
	  * telefono
	  * 
	  * El telefono de la sucursal<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $telefono;

	/**
	  * token
	  * 
	  * Token de seguridad para esta sucursal<br>
	  * @access protected
	  * @var varchar(512)
	  */
	protected $token;

	/**
	  * letras_factura
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var char(1)
	  */
	protected $letras_factura;

	/**
	  * activo
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activo;

	/**
	  * fecha_apertura
	  * 
	  * Fecha de apertura de esta sucursal<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_apertura;

	/**
	  * saldo_a_favor
	  * 
	  * es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras<br>
	  * @access protected
	  * @var float
	  */
	protected $saldo_a_favor;

	/**
	  * current_isp
	  * 
	  * el proveedor de servicios de internet, si se cambia de isp, no lo dejare entrar hasta que un inge lo actalize<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $current_isp;

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Identificador de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getGerente
	  * 
	  * Get the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal
	  * @return int(11)
	  */
	final public function getGerente()
	{
		return $this->gerente;
	}

	/**
	  * setGerente( $gerente )
	  * 
	  * Set the <i>gerente</i> property for this object. Donde <i>gerente</i> es Gerente de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>gerente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setGerente( $gerente )
	{
		$this->gerente = $gerente;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal
	  * @return varchar(100)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es nombre o descripcion de sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal
	  * @return varchar(100)
	  */
	final public function getRazonSocial()
	{
		return $this->razon_social;
	}

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es razon social de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setRazonSocial( $razon_social )
	{
		$this->razon_social = $razon_social;
	}

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal
	  * @return varchar(20)
	  */
	final public function getRfc()
	{
		return $this->rfc;
	}

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es El RFC de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setRfc( $rfc )
	{
		$this->rfc = $rfc;
	}

	/**
	  * getCalle
	  * 
	  * Get the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getCalle()
	{
		return $this->calle;
	}

	/**
	  * setCalle( $calle )
	  * 
	  * Set the <i>calle</i> property for this object. Donde <i>calle</i> es calle del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>calle</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setCalle( $calle )
	{
		$this->calle = $calle;
	}

	/**
	  * getNumeroExterior
	  * 
	  * Get the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal
	  * @return varchar(10)
	  */
	final public function getNumeroExterior()
	{
		return $this->numero_exterior;
	}

	/**
	  * setNumeroExterior( $numero_exterior )
	  * 
	  * Set the <i>numero_exterior</i> property for this object. Donde <i>numero_exterior</i> es nuemro exterior del domicilio fiscal.
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
	  * Get the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal
	  * @return varchar(10)
	  */
	final public function getNumeroInterior()
	{
		return $this->numero_interior;
	}

	/**
	  * setNumeroInterior( $numero_interior )
	  * 
	  * Set the <i>numero_interior</i> property for this object. Donde <i>numero_interior</i> es numero interior del domicilio fiscal.
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
	  * Get the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getColonia()
	{
		return $this->colonia;
	}

	/**
	  * setColonia( $colonia )
	  * 
	  * Set the <i>colonia</i> property for this object. Donde <i>colonia</i> es colonia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>colonia</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setColonia( $colonia )
	{
		$this->colonia = $colonia;
	}

	/**
	  * getLocalidad
	  * 
	  * Get the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getLocalidad()
	{
		return $this->localidad;
	}

	/**
	  * setLocalidad( $localidad )
	  * 
	  * Set the <i>localidad</i> property for this object. Donde <i>localidad</i> es localidad del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>localidad</i> es de tipo <i>varchar(50)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(50)
	  */
	final public function setLocalidad( $localidad )
	{
		$this->localidad = $localidad;
	}

	/**
	  * getReferencia
	  * 
	  * Get the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal
	  * @return varchar(200)
	  */
	final public function getReferencia()
	{
		return $this->referencia;
	}

	/**
	  * setReferencia( $referencia )
	  * 
	  * Set the <i>referencia</i> property for this object. Donde <i>referencia</i> es referencia del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>referencia</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	final public function setReferencia( $referencia )
	{
		$this->referencia = $referencia;
	}

	/**
	  * getMunicipio
	  * 
	  * Get the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal
	  * @return varchar(100)
	  */
	final public function getMunicipio()
	{
		return $this->municipio;
	}

	/**
	  * setMunicipio( $municipio )
	  * 
	  * Set the <i>municipio</i> property for this object. Donde <i>municipio</i> es municipio del domicilio fiscal.
	  * Una validacion basica se hara aqui para comprobar que <i>municipio</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setMunicipio( $municipio )
	{
		$this->municipio = $municipio;
	}

	/**
	  * getEstado
	  * 
	  * Get the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getEstado()
	{
		return $this->estado;
	}

	/**
	  * setEstado( $estado )
	  * 
	  * Set the <i>estado</i> property for this object. Donde <i>estado</i> es estado del domicilio fiscal.
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
	  * Get the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal
	  * @return varchar(50)
	  */
	final public function getPais()
	{
		return $this->pais;
	}

	/**
	  * setPais( $pais )
	  * 
	  * Set the <i>pais</i> property for this object. Donde <i>pais</i> es pais del domicilio fiscal.
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
	  * Get the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal
	  * @return varchar(15)
	  */
	final public function getCodigoPostal()
	{
		return $this->codigo_postal;
	}

	/**
	  * setCodigoPostal( $codigo_postal )
	  * 
	  * Set the <i>codigo_postal</i> property for this object. Donde <i>codigo_postal</i> es codigo postal del domicilio fiscal.
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
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal
	  * @return varchar(20)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es El telefono de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>telefono</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setTelefono( $telefono )
	{
		$this->telefono = $telefono;
	}

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal
	  * @return varchar(512)
	  */
	final public function getToken()
	{
		return $this->token;
	}

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es Token de seguridad para esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	final public function setToken( $token )
	{
		$this->token = $token;
	}

	/**
	  * getLetrasFactura
	  * 
	  * Get the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado]
	  * @return char(1)
	  */
	final public function getLetrasFactura()
	{
		return $this->letras_factura;
	}

	/**
	  * setLetrasFactura( $letras_factura )
	  * 
	  * Set the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>letras_factura</i> es de tipo <i>char(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param char(1)
	  */
	final public function setLetrasFactura( $letras_factura )
	{
		$this->letras_factura = $letras_factura;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getFechaApertura
	  * 
	  * Get the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal
	  * @return timestamp
	  */
	final public function getFechaApertura()
	{
		return $this->fecha_apertura;
	}

	/**
	  * setFechaApertura( $fecha_apertura )
	  * 
	  * Set the <i>fecha_apertura</i> property for this object. Donde <i>fecha_apertura</i> es Fecha de apertura de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_apertura</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaApertura( $fecha_apertura )
	{
		$this->fecha_apertura = $fecha_apertura;
	}

	/**
	  * getSaldoAFavor
	  * 
	  * Get the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras
	  * @return float
	  */
	final public function getSaldoAFavor()
	{
		return $this->saldo_a_favor;
	}

	/**
	  * setSaldoAFavor( $saldo_a_favor )
	  * 
	  * Set the <i>saldo_a_favor</i> property for this object. Donde <i>saldo_a_favor</i> es es el saldo a favor que tiene la sucursal encuanto a los abonos de sus compras.
	  * Una validacion basica se hara aqui para comprobar que <i>saldo_a_favor</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setSaldoAFavor( $saldo_a_favor )
	{
		$this->saldo_a_favor = $saldo_a_favor;
	}

	/**
	  * getCurrentIsp
	  * 
	  * Get the <i>current_isp</i> property for this object. Donde <i>current_isp</i> es el proveedor de servicios de internet, si se cambia de isp, no lo dejare entrar hasta que un inge lo actalize
	  * @return varchar(256)
	  */
	final public function getCurrentIsp()
	{
		return $this->current_isp;
	}

	/**
	  * setCurrentIsp( $current_isp )
	  * 
	  * Set the <i>current_isp</i> property for this object. Donde <i>current_isp</i> es el proveedor de servicios de internet, si se cambia de isp, no lo dejare entrar hasta que un inge lo actalize.
	  * Una validacion basica se hara aqui para comprobar que <i>current_isp</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setCurrentIsp( $current_isp )
	{
		$this->current_isp = $current_isp;
	}

}
