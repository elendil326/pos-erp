<?php
/** Value Object file for table unidad_equivalencia.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class UnidadEquivalencia extends VO
{
	/**
	  * Constructor de UnidadEquivalencia
	  * 
	  * Para construir un objeto de tipo UnidadEquivalencia debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return UnidadEquivalencia
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_unidad']) ){
				$this->id_unidad = $data['id_unidad'];
			}
			if( isset($data['equivalencia']) ){
				$this->equivalencia = $data['equivalencia'];
			}
			if( isset($data['id_unidades']) ){
				$this->id_unidades = $data['id_unidades'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto UnidadEquivalencia en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_unidad" => $this->id_unidad,
			"equivalencia" => $this->equivalencia,
			"id_unidades" => $this->id_unidades
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_unidad
	  * 
	  * Id de la unidad origen<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_unidad;

	/**
	  * equivalencia
	  * 
	  * Numero de unidades de id_unidades que caben en la unidad id_unidad<br>
	  * @access public
	  * @var float
	  */
	public $equivalencia;

	/**
	  * id_unidades
	  * 
	  * Id de las unidades equivalentes<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_unidades;

	/**
	  * getIdUnidad
	  * 
	  * Get the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la unidad origen
	  * @return int(11)
	  */
	final public function getIdUnidad()
	{
		return $this->id_unidad;
	}

	/**
	  * setIdUnidad( $id_unidad )
	  * 
	  * Set the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la unidad origen.
	  * Una validacion basica se hara aqui para comprobar que <i>id_unidad</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUnidad( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdUnidad( $id_unidad )
	{
		$this->id_unidad = $id_unidad;
	}

	/**
	  * getEquivalencia
	  * 
	  * Get the <i>equivalencia</i> property for this object. Donde <i>equivalencia</i> es Numero de unidades de id_unidades que caben en la unidad id_unidad
	  * @return float
	  */
	final public function getEquivalencia()
	{
		return $this->equivalencia;
	}

	/**
	  * setEquivalencia( $equivalencia )
	  * 
	  * Set the <i>equivalencia</i> property for this object. Donde <i>equivalencia</i> es Numero de unidades de id_unidades que caben en la unidad id_unidad.
	  * Una validacion basica se hara aqui para comprobar que <i>equivalencia</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setEquivalencia( $equivalencia )
	{
		$this->equivalencia = $equivalencia;
	}

	/**
	  * getIdUnidades
	  * 
	  * Get the <i>id_unidades</i> property for this object. Donde <i>id_unidades</i> es Id de las unidades equivalentes
	  * @return int(11)
	  */
	final public function getIdUnidades()
	{
		return $this->id_unidades;
	}

	/**
	  * setIdUnidades( $id_unidades )
	  * 
	  * Set the <i>id_unidades</i> property for this object. Donde <i>id_unidades</i> es Id de las unidades equivalentes.
	  * Una validacion basica se hara aqui para comprobar que <i>id_unidades</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUnidades( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdUnidades( $id_unidades )
	{
		$this->id_unidades = $id_unidades;
	}

}
