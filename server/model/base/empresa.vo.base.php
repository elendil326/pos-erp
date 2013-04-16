<?php
/** Value Object file for table empresa.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class Empresa extends VO
{
	/**
	  * Constructor de Empresa
	  * 
	  * Para construir un objeto de tipo Empresa debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Empresa
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_empresa']) ){
				$this->id_empresa = $data['id_empresa'];
			}
			if( isset($data['id_direccion']) ){
				$this->id_direccion = $data['id_direccion'];
			}
			if( isset($data['rfc']) ){
				$this->rfc = $data['rfc'];
			}
			if( isset($data['razon_social']) ){
				$this->razon_social = $data['razon_social'];
			}
			if( isset($data['representante_legal']) ){
				$this->representante_legal = $data['representante_legal'];
			}
			if( isset($data['fecha_alta']) ){
				$this->fecha_alta = $data['fecha_alta'];
			}
			if( isset($data['fecha_baja']) ){
				$this->fecha_baja = $data['fecha_baja'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['direccion_web']) ){
				$this->direccion_web = $data['direccion_web'];
			}
			if( isset($data['cedula']) ){
				$this->cedula = $data['cedula'];
			}
			if( isset($data['id_logo']) ){
				$this->id_logo = $data['id_logo'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Empresa en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_empresa" => $this->id_empresa,
			"id_direccion" => $this->id_direccion,
			"rfc" => $this->rfc,
			"razon_social" => $this->razon_social,
			"representante_legal" => $this->representante_legal,
			"fecha_alta" => $this->fecha_alta,
			"fecha_baja" => $this->fecha_baja,
			"activo" => $this->activo,
			"direccion_web" => $this->direccion_web,
			"cedula" => $this->cedula,
			"id_logo" => $this->id_logo
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_empresa
	  * 
	  * Id de la tabla empresa<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_empresa;

	/**
	  * id_direccion
	  * 
	  * Id de la direccion de la empresa<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_direccion;

	/**
	  * rfc
	  * 
	  * RFC de la empresa<br>
	  * @access public
	  * @var varchar(30)
	  */
	public $rfc;

	/**
	  * razon_social
	  * 
	  * Razon social de la empresa<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $razon_social;

	/**
	  * representante_legal
	  * 
	  * Representante legal de la empresa, puede ser persona o empresa<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $representante_legal;

	/**
	  * fecha_alta
	  * 
	  * Fecha en que se creo esta empresa<br>
	  * @access public
	  * @var int(11)
	  */
	public $fecha_alta;

	/**
	  * fecha_baja
	  * 
	  * Fecha en que se desactivo esa empresa<br>
	  * @access public
	  * @var int(11)
	  */
	public $fecha_baja;

	/**
	  * activo
	  * 
	  * Si esta empresa esta activa o no<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $activo;

	/**
	  * direccion_web
	  * 
	  * Direccion web de la empresa<br>
	  * @access public
	  * @var varchar(20)
	  */
	public $direccion_web;

	/**
	  * cedula
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $cedula;

	/**
	  * id_logo
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_logo;

	/**
	  * getIdEmpresa
	  * 
	  * Get the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es Id de la tabla empresa
	  * @return int(11)
	  */
	final public function getIdEmpresa()
	{
		return $this->id_empresa;
	}

	/**
	  * setIdEmpresa( $id_empresa )
	  * 
	  * Set the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es Id de la tabla empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>id_empresa</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdEmpresa( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEmpresa( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEmpresa( $id_empresa )
	{
		$this->id_empresa = $id_empresa;
	}

	/**
	  * getIdDireccion
	  * 
	  * Get the <i>id_direccion</i> property for this object. Donde <i>id_direccion</i> es Id de la direccion de la empresa
	  * @return int(11)
	  */
	final public function getIdDireccion()
	{
		return $this->id_direccion;
	}

	/**
	  * setIdDireccion( $id_direccion )
	  * 
	  * Set the <i>id_direccion</i> property for this object. Donde <i>id_direccion</i> es Id de la direccion de la empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>id_direccion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdDireccion( $id_direccion )
	{
		$this->id_direccion = $id_direccion;
	}

	/**
	  * getRfc
	  * 
	  * Get the <i>rfc</i> property for this object. Donde <i>rfc</i> es RFC de la empresa
	  * @return varchar(30)
	  */
	final public function getRfc()
	{
		return $this->rfc;
	}

	/**
	  * setRfc( $rfc )
	  * 
	  * Set the <i>rfc</i> property for this object. Donde <i>rfc</i> es RFC de la empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>rfc</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	final public function setRfc( $rfc )
	{
		$this->rfc = $rfc;
	}

	/**
	  * getRazonSocial
	  * 
	  * Get the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es Razon social de la empresa
	  * @return varchar(100)
	  */
	final public function getRazonSocial()
	{
		return $this->razon_social;
	}

	/**
	  * setRazonSocial( $razon_social )
	  * 
	  * Set the <i>razon_social</i> property for this object. Donde <i>razon_social</i> es Razon social de la empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>razon_social</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setRazonSocial( $razon_social )
	{
		$this->razon_social = $razon_social;
	}

	/**
	  * getRepresentanteLegal
	  * 
	  * Get the <i>representante_legal</i> property for this object. Donde <i>representante_legal</i> es Representante legal de la empresa, puede ser persona o empresa
	  * @return varchar(100)
	  */
	final public function getRepresentanteLegal()
	{
		return $this->representante_legal;
	}

	/**
	  * setRepresentanteLegal( $representante_legal )
	  * 
	  * Set the <i>representante_legal</i> property for this object. Donde <i>representante_legal</i> es Representante legal de la empresa, puede ser persona o empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>representante_legal</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setRepresentanteLegal( $representante_legal )
	{
		$this->representante_legal = $representante_legal;
	}

	/**
	  * getFechaAlta
	  * 
	  * Get the <i>fecha_alta</i> property for this object. Donde <i>fecha_alta</i> es Fecha en que se creo esta empresa
	  * @return int(11)
	  */
	final public function getFechaAlta()
	{
		return $this->fecha_alta;
	}

	/**
	  * setFechaAlta( $fecha_alta )
	  * 
	  * Set the <i>fecha_alta</i> property for this object. Donde <i>fecha_alta</i> es Fecha en que se creo esta empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_alta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setFechaAlta( $fecha_alta )
	{
		$this->fecha_alta = $fecha_alta;
	}

	/**
	  * getFechaBaja
	  * 
	  * Get the <i>fecha_baja</i> property for this object. Donde <i>fecha_baja</i> es Fecha en que se desactivo esa empresa
	  * @return int(11)
	  */
	final public function getFechaBaja()
	{
		return $this->fecha_baja;
	}

	/**
	  * setFechaBaja( $fecha_baja )
	  * 
	  * Set the <i>fecha_baja</i> property for this object. Donde <i>fecha_baja</i> es Fecha en que se desactivo esa empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_baja</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setFechaBaja( $fecha_baja )
	{
		$this->fecha_baja = $fecha_baja;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Si esta empresa esta activa o no
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Si esta empresa esta activa o no.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getDireccionWeb
	  * 
	  * Get the <i>direccion_web</i> property for this object. Donde <i>direccion_web</i> es Direccion web de la empresa
	  * @return varchar(20)
	  */
	final public function getDireccionWeb()
	{
		return $this->direccion_web;
	}

	/**
	  * setDireccionWeb( $direccion_web )
	  * 
	  * Set the <i>direccion_web</i> property for this object. Donde <i>direccion_web</i> es Direccion web de la empresa.
	  * Una validacion basica se hara aqui para comprobar que <i>direccion_web</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setDireccionWeb( $direccion_web )
	{
		$this->direccion_web = $direccion_web;
	}

	/**
	  * getCedula
	  * 
	  * Get the <i>cedula</i> property for this object. Donde <i>cedula</i> es  [Campo no documentado]
	  * @return varchar(100)
	  */
	final public function getCedula()
	{
		return $this->cedula;
	}

	/**
	  * setCedula( $cedula )
	  * 
	  * Set the <i>cedula</i> property for this object. Donde <i>cedula</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cedula</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setCedula( $cedula )
	{
		$this->cedula = $cedula;
	}

	/**
	  * getIdLogo
	  * 
	  * Get the <i>id_logo</i> property for this object. Donde <i>id_logo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdLogo()
	{
		return $this->id_logo;
	}

	/**
	  * setIdLogo( $id_logo )
	  * 
	  * Set the <i>id_logo</i> property for this object. Donde <i>id_logo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_logo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdLogo( $id_logo )
	{
		$this->id_logo = $id_logo;
	}

}
