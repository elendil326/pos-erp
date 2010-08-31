<?php
/** Value Object file for table proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Proveedor extends VO
{
	/**
	  * Constructor de Proveedor
	  * 
	  * Para construir un objeto de tipo Proveedor debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Proveedor
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_proveedor = $data['id_proveedor'];
			$this->rfc = $data['rfc'];
			$this->nombre = $data['nombre'];
			$this->direccion = $data['direccion'];
			$this->telefono = $data['telefono'];
			$this->e_mail = $data['e_mail'];
			$this->activo = $data['activo'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Proveedor en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_proveedor" => $this->id_proveedor,
		"rfc" => $this->rfc,
		"nombre" => $this->nombre,
		"direccion" => $this->direccion,
		"telefono" => $this->telefono,
		"e_mail" => $this->e_mail,
		"activo" => $this->activo
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_proveedor
	  * 
	  * identificador del proveedor<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_proveedor;

	/**
	  * rfc
	  * 
	  * rfc del proveedor<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $rfc;

	/**
	  * nombre
	  * 
	  * nombre del proveedor<br>
	  * @access protected
	  * @var varchar(30)
	  */
	protected $nombre;

	/**
	  * direccion
	  * 
	  * direccion del proveedor<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $direccion;

	/**
	  * telefono
	  * 
	  * telefono<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $telefono;

	/**
	  * e_mail
	  * 
	  * email del provedor<br>
	  * @access protected
	  * @var varchar(60)
	  */
	protected $e_mail;

	/**
	  * activo
	  * 
	  * Indica si la cuenta esta activada o desactivada<br>
	  * @access protected
	  * @var tinyint(2)
	  */
	protected $activo;

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es identificador del proveedor
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es identificador del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
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
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del proveedor
	  * @return varchar(30)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDireccion
	  * 
	  * Get the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion del proveedor
	  * @return varchar(100)
	  */
	final public function getDireccion()
	{
		return $this->direccion;
	}

	/**
	  * setDireccion( $direccion )
	  * 
	  * Set the <i>direccion</i> property for this object. Donde <i>direccion</i> es direccion del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setDireccion( $direccion )
	{
		$this->direccion = $direccion;
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
	  * getEMail
	  * 
	  * Get the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es email del provedor
	  * @return varchar(60)
	  */
	final public function getEMail()
	{
		return $this->e_mail;
	}

	/**
	  * setEMail( $e_mail )
	  * 
	  * Set the <i>e_mail</i> property for this object. Donde <i>e_mail</i> es email del provedor.
	  * Una validacion basica se hara aqui para comprobar que <i>e_mail</i> es de tipo <i>varchar(60)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(60)
	  */
	final public function setEMail( $e_mail )
	{
		$this->e_mail = $e_mail;
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

}
