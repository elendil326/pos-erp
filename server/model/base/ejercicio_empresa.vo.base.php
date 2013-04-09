<?php
/** Value Object file for table ejercicio_empresa.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class EjercicioEmpresa extends VO
{
	/**
	  * Constructor de EjercicioEmpresa
	  * 
	  * Para construir un objeto de tipo EjercicioEmpresa debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return EjercicioEmpresa
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_ejercicio']) ){
				$this->id_ejercicio = $data['id_ejercicio'];
			}
			if( isset($data['id_empresa']) ){
				$this->id_empresa = $data['id_empresa'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto EjercicioEmpresa en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_ejercicio" => $this->id_ejercicio,
			"id_empresa" => $this->id_empresa
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_ejercicio
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_ejercicio;

	/**
	  * id_empresa
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_empresa;

	/**
	  * getIdEjercicio
	  * 
	  * Get the <i>id_ejercicio</i> property for this object. Donde <i>id_ejercicio</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdEjercicio()
	{
		return $this->id_ejercicio;
	}

	/**
	  * setIdEjercicio( $id_ejercicio )
	  * 
	  * Set the <i>id_ejercicio</i> property for this object. Donde <i>id_ejercicio</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_ejercicio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEjercicio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEjercicio( $id_ejercicio )
	{
		$this->id_ejercicio = $id_ejercicio;
	}

	/**
	  * getIdEmpresa
	  * 
	  * Get the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdEmpresa()
	{
		return $this->id_empresa;
	}

	/**
	  * setIdEmpresa( $id_empresa )
	  * 
	  * Set the <i>id_empresa</i> property for this object. Donde <i>id_empresa</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_empresa</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEmpresa( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEmpresa( $id_empresa )
	{
		$this->id_empresa = $id_empresa;
	}

}
