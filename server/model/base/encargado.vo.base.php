<?php
/** Value Object file for table encargado.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Encargado extends VO
{
	/**
	  * Constructor de Encargado
	  * 
	  * Para construir un objeto de tipo Encargado debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Encargado
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_usuario = $data['id_usuario'];
			$this->porciento = $data['porciento'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Encargado en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_usuario" => $this->id_usuario,
		"porciento" => $this->porciento
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_usuario
	  * 
	  * Este id es el del usuario encargado de su sucursal<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * porciento
	  * 
	  * este es el porciento de las ventas que le tocan al encargado<br>
	  * @access protected
	  * @var float
	  */
	protected $porciento;

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Este id es el del usuario encargado de su sucursal
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Este id es el del usuario encargado de su sucursal.
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
	  * getPorciento
	  * 
	  * Get the <i>porciento</i> property for this object. Donde <i>porciento</i> es este es el porciento de las ventas que le tocan al encargado
	  * @return float
	  */
	final public function getPorciento()
	{
		return $this->porciento;
	}

	/**
	  * setPorciento( $porciento )
	  * 
	  * Set the <i>porciento</i> property for this object. Donde <i>porciento</i> es este es el porciento de las ventas que le tocan al encargado.
	  * Una validacion basica se hara aqui para comprobar que <i>porciento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPorciento( $porciento )
	{
		$this->porciento = $porciento;
	}

}
