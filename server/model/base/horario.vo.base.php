<?php
/** Value Object file for table horario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Horario extends VO
{
	/**
	  * Constructor de Horario
	  * 
	  * Para construir un objeto de tipo Horario debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Horario
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_usuario = $data['id_usuario'];
			$this->accion = $data['accion'];
			$this->fecha = $data['fecha'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Horario en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	  public function __toString( )
	  { 
		$vec = array( 
		"id_usuario" => $this->id_usuario,
		"accion" => $this->accion,
		"fecha" => $this->fecha
		); 
	return json_encode($vec); 
	}
	/**
	  * id_usuario
	  * 
	  * Usuario<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * accion
	  * 
	  * Accion de entrada o salida<br>
	  * @access protected
	  * @var enum('entrada','salida')
	  */
	protected $accion;

	/**
	  * fecha
	  * 
	  * Fecha de accion<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Usuario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUsuario( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getAccion
	  * 
	  * Get the <i>accion</i> property for this object. Donde <i>accion</i> es Accion de entrada o salida
	  * @return enum('entrada','salida')
	  */
	final public function getAccion()
	{
		return $this->accion;
	}

	/**
	  * setAccion( $accion )
	  * 
	  * Set the <i>accion</i> property for this object. Donde <i>accion</i> es Accion de entrada o salida.
	  * Una validacion basica se hara aqui para comprobar que <i>accion</i> es de tipo <i>enum('entrada','salida')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('entrada','salida')
	  */
	final public function setAccion( $accion )
	{
		$this->accion = $accion;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha de accion
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha de accion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setFecha( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

}
