<?php
/** Value Object file for table pos_config.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author caffeina
  * @access public
  * @package docs
  * 
  */

class PosConfig extends VO
{
	/**
	  * Constructor de PosConfig
	  * 
	  * Para construir un objeto de tipo PosConfig debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PosConfig
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['opcion']) ){
				$this->opcion = $data['opcion'];
			}
			if( isset($data['value']) ){
				$this->value = $data['value'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PosConfig en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"opcion" => $this->opcion,
			"value" => $this->value
		); 
	return json_encode($vec); 
	}
	
	/**
	  * opcion
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var varchar(30)
	  */
	protected $opcion;

	/**
	  * value
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(2048)
	  */
	protected $value;

	/**
	  * getOpcion
	  * 
	  * Get the <i>opcion</i> property for this object. Donde <i>opcion</i> es  [Campo no documentado]
	  * @return varchar(30)
	  */
	final public function getOpcion()
	{
		return $this->opcion;
	}

	/**
	  * setOpcion( $opcion )
	  * 
	  * Set the <i>opcion</i> property for this object. Donde <i>opcion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>opcion</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setOpcion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param varchar(30)
	  */
	final public function setOpcion( $opcion )
	{
		$this->opcion = $opcion;
	}

	/**
	  * getValue
	  * 
	  * Get the <i>value</i> property for this object. Donde <i>value</i> es  [Campo no documentado]
	  * @return varchar(2048)
	  */
	final public function getValue()
	{
		return $this->value;
	}

	/**
	  * setValue( $value )
	  * 
	  * Set the <i>value</i> property for this object. Donde <i>value</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>value</i> es de tipo <i>varchar(2048)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(2048)
	  */
	final public function setValue( $value )
	{
		$this->value = $value;
	}

}
