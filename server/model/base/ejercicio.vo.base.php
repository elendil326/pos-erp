<?php
/** Value Object file for table ejercicio.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class Ejercicio extends VO
{
	/**
	  * Constructor de Ejercicio
	  * 
	  * Para construir un objeto de tipo Ejercicio debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Ejercicio
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_ejercicio']) ){
				$this->id_ejercicio = $data['id_ejercicio'];
			}
			if( isset($data['anio']) ){
				$this->anio = $data['anio'];
			}
			if( isset($data['id_periodo']) ){
				$this->id_periodo = $data['id_periodo'];
			}
			if( isset($data['inicio']) ){
				$this->inicio = $data['inicio'];
			}
			if( isset($data['fin']) ){
				$this->fin = $data['fin'];
			}
			if( isset($data['vigente']) ){
				$this->vigente = $data['vigente'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Ejercicio en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_ejercicio" => $this->id_ejercicio,
			"anio" => $this->anio,
			"id_periodo" => $this->id_periodo,
			"inicio" => $this->inicio,
			"fin" => $this->fin,
			"vigente" => $this->vigente
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_ejercicio
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_ejercicio;

	/**
	  * anio
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(4)
	  */
	public $anio;

	/**
	  * id_periodo
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_periodo;

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
	  * vigente
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $vigente;

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
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdEjercicio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEjercicio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEjercicio( $id_ejercicio )
	{
		$this->id_ejercicio = $id_ejercicio;
	}

	/**
	  * getAnio
	  * 
	  * Get the <i>anio</i> property for this object. Donde <i>anio</i> es  [Campo no documentado]
	  * @return int(4)
	  */
	final public function getAnio()
	{
		return $this->anio;
	}

	/**
	  * setAnio( $anio )
	  * 
	  * Set the <i>anio</i> property for this object. Donde <i>anio</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>anio</i> es de tipo <i>int(4)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(4)
	  */
	final public function setAnio( $anio )
	{
		$this->anio = $anio;
	}

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
	  * @param int(11)
	  */
	final public function setIdPeriodo( $id_periodo )
	{
		$this->id_periodo = $id_periodo;
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

	/**
	  * getVigente
	  * 
	  * Get the <i>vigente</i> property for this object. Donde <i>vigente</i> es  [Campo no documentado]
	  * @return tinyint(1)
	  */
	final public function getVigente()
	{
		return $this->vigente;
	}

	/**
	  * setVigente( $vigente )
	  * 
	  * Set the <i>vigente</i> property for this object. Donde <i>vigente</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>vigente</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setVigente( $vigente )
	{
		$this->vigente = $vigente;
	}

}
