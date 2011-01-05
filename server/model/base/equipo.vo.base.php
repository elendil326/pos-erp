<?php
/** Value Object file for table equipo.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class Equipo extends VO
{
	/**
	  * Constructor de Equipo
	  * 
	  * Para construir un objeto de tipo Equipo debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Equipo
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_equipo']) ){
				$this->id_equipo = $data['id_equipo'];
			}
			if( isset($data['token']) ){
				$this->token = $data['token'];
			}
			if( isset($data['full_ua']) ){
				$this->full_ua = $data['full_ua'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Equipo en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_equipo" => $this->id_equipo,
		"token" => $this->token,
		"full_ua" => $this->full_ua
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_equipo
	  * 
	  * el identificador de este equipo<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(6)
	  */
	protected $id_equipo;

	/**
	  * token
	  * 
	  * el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $token;

	/**
	  * full_ua
	  * 
	  * String de user-agent para este cliente<br>
	  * @access protected
	  * @var varchar(256)
	  */
	protected $full_ua;

	/**
	  * getIdEquipo
	  * 
	  * Get the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo
	  * @return int(6)
	  */
	final public function getIdEquipo()
	{
		return $this->id_equipo;
	}

	/**
	  * setIdEquipo( $id_equipo )
	  * 
	  * Set the <i>id_equipo</i> property for this object. Donde <i>id_equipo</i> es el identificador de este equipo.
	  * Una validacion basica se hara aqui para comprobar que <i>id_equipo</i> es de tipo <i>int(6)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEquipo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(6)
	  */
	final public function setIdEquipo( $id_equipo )
	{
		$this->id_equipo = $id_equipo;
	}

	/**
	  * getToken
	  * 
	  * Get the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado
	  * @return varchar(128)
	  */
	final public function getToken()
	{
		return $this->token;
	}

	/**
	  * setToken( $token )
	  * 
	  * Set the <i>token</i> property for this object. Donde <i>token</i> es el token de seguridad que identifica a este equipo unicamente, representado generalmente por un user-agent modificado.
	  * Una validacion basica se hara aqui para comprobar que <i>token</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setToken( $token )
	{
		$this->token = $token;
	}

	/**
	  * getFullUa
	  * 
	  * Get the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente
	  * @return varchar(256)
	  */
	final public function getFullUa()
	{
		return $this->full_ua;
	}

	/**
	  * setFullUa( $full_ua )
	  * 
	  * Set the <i>full_ua</i> property for this object. Donde <i>full_ua</i> es String de user-agent para este cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>full_ua</i> es de tipo <i>varchar(256)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(256)
	  */
	final public function setFullUa( $full_ua )
	{
		$this->full_ua = $full_ua;
	}

}
