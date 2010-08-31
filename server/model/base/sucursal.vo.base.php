<?php
/** Value Object file for table sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
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
			$this->id_sucursal = $data['id_sucursal'];
			$this->descripcion = $data['descripcion'];
			$this->direccion = $data['direccion'];
			$this->rfc = $data['rfc'];
			$this->e_mail = $data['e_mail'];
			$this->telefono = $data['telefono'];
			$this->token = $data['token'];
			$this->letras_factura = $data['letras_factura'];
			$this->activo = $data['activo'];
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
		$vec = array();
		array_push($vec, array( 
		"id_sucursal" => $this->id_sucursal,
		"descripcion" => $this->descripcion,
		"direccion" => $this->direccion,
		"rfc" => $this->rfc,
		"e_mail" => $this->e_mail,
		"telefono" => $this->telefono,
		"token" => $this->token,
		"letras_factura" => $this->letras_factura,
		"activo" => $this->activo
		)); 
	return json_encode($vec, true); 
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
	  * descripcion
	  * 
	  * nombre o descripcion de sucursal<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $descripcion;

	/**
	  * direccion
	  * 
	  * direccion de la sucursal<br>
	  * @access protected
	  * @var varchar(200)
	  */
	protected $direccion;

	/**
	  * rfc
	  * 
	  * rfc del proveedor<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $rfc;

	/**
	  * e_mail
	  * 
	  * direccion de correo electronico<br>
	  * @access protected
	  * @var varchar(200)
	  */
	protected $e_mail;

	/**
	  * telefono
	  * 
	  * telefono<br>
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
	  * Campo no documentado<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $letras_factura;

	/**
	  * activo
	  * 
	  * Campo no documentado<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activo;

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
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion de la sucursal
	  * @return varchar(200)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
	}

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del proveedor
	  * @return varchar(20)
	  */
	final public function getRfc()
	{
		return $this->rfc;
	}

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es rfc del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setRfc( $rfc )
	{
		$this->rfc = $rfc;
	}

	/**
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es direccion de correo electronico
	  * @return varchar(200)
	  */
	final public function getEMail()
	{
		return $this->e_mail;
	}

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es direccion de correo electronico.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	final public function setEMail( $e_mail )
	{
		$this->e_mail = $e_mail;
	}

	/**
	  * getTelefono
	  * 
	  * Get the <i>telefono</i> property for this object. Donde <i>telefono</i> es telefono
	  * @return varchar(20)
	  */
	final public function getTelefono()
	{
		return $this->telefono;
	}

	/**
	  * setTelefono( $telefono )
	  * 
	  * Set the <i>telefono</i> property for this object. Donde <i>telefono</i> es telefono.
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
	  * Get the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es Campo no documentado
	  * @return varchar(10)
	  */
	final public function getLetrasFactura()
	{
		return $this->letras_factura;
	}

	/**
	  * setLetrasFactura( $letras_factura )
	  * 
	  * Set the <i>letras_factura</i> property for this object. Donde <i>letras_factura</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>letras_factura</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	final public function setLetrasFactura( $letras_factura )
	{
		$this->letras_factura = $letras_factura;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Campo no documentado
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Campo no documentado.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

}
