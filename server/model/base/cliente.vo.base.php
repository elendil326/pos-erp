<?php
/** Value Object file for table cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
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
			$this->id_cliente = $data['id_cliente'];
			$this->rfc = $data['rfc'];
			$this->nombre = $data['nombre'];
			$this->direccion = $data['direccion'];
			$this->telefono = $data['telefono'];
			$this->e_mail = $data['e_mail'];
			$this->limite_credito = $data['limite_credito'];
			$this->descuento = $data['descuento'];
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
		$vec = array();
		array_push($vec, array( 
		"id_cliente" => $this->id_cliente,
		"rfc" => $this->rfc,
		"nombre" => $this->nombre,
		"direccion" => $this->direccion,
		"telefono" => $this->telefono,
		"e_mail" => $this->e_mail,
		"limite_credito" => $this->limite_credito,
		"descuento" => $this->descuento
		)); 
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
	  * Taza porcentual de descuento de 0 a 100<br>
	  * @access protected
	  * @var tinyint(4)
	  */
	protected $descuento;

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
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0 a 100
	  * @return tinyint(4)
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Taza porcentual de descuento de 0 a 100.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>tinyint(4)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(4)
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

}
