<?php
/** Value Object file for table periodo.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class Periodo extends VO
{
	/**
	  * Constructor de Periodo
	  * 
	  * Para construir un objeto de tipo Periodo debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Periodo
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_periodo']) ){
				$this->id_periodo = $data['id_periodo'];
			}
			if( isset($data['periodo']) ){
				$this->periodo = $data['periodo'];
			}
			if( isset($data['inicio']) ){
				$this->inicio = $data['inicio'];
			}
			if( isset($data['fin']) ){
				$this->fin = $data['fin'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Periodo en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_periodo" => $this->id_periodo,
			"periodo" => $this->periodo,
			"inicio" => $this->inicio,
			"fin" => $this->fin
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_periodo
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_periodo;

	/**
	  * periodo
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(2)
	  */
	public $periodo;

	/**
	  * inicio
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(11)
	  */
	public $inicio;

	/**
	  * fin
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(11)
	  */
	public $fin;

	/**
	  * getIdPeriodo
	  * 
	  * Get the <i>id_periodo</i> property for this object. Donde <i>id_periodo</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdPeriodo()
	{
		return $this->id_periodo;
	}

	/**
	  * setIdPeriodo( $id_periodo )
	  * 
	  * Set the <i>id_periodo</i> property for this object. Donde <i>id_periodo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_periodo</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdPeriodo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPeriodo( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPeriodo( $id_periodo )
	{
		$this->id_periodo = $id_periodo;
	}

	/**
	  * getPeriodo
	  * 
	  * Get the <i>periodo</i> property for this object. Donde <i>periodo</i> es  [Campo no documentado]
	  * @return int(2)
	  */
	final public function getPeriodo()
	{
		return $this->periodo;
	}

	/**
	  * setPeriodo( $periodo )
	  * 
	  * Set the <i>periodo</i> property for this object. Donde <i>periodo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>periodo</i> es de tipo <i>int(2)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(2)
	  */
	final public function setPeriodo( $periodo )
	{
		$this->periodo = $periodo;
	}

	/**
	  * getInicio
	  * 
	  * Get the <i>inicio</i> property for this object. Donde <i>inicio</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getInicio()
	{
		return $this->inicio;
	}

	/**
	  * setInicio( $inicio )
	  * 
	  * Set the <i>inicio</i> property for this object. Donde <i>inicio</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>inicio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setInicio( $inicio )
	{
		$this->inicio = $inicio;
	}

	/**
	  * getFin
	  * 
	  * Get the <i>fin</i> property for this object. Donde <i>fin</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getFin()
	{
		return $this->fin;
	}

	/**
	  * setFin( $fin )
	  * 
	  * Set the <i>fin</i> property for this object. Donde <i>fin</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fin</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setFin( $fin )
	{
		$this->fin = $fin;
	}

}
