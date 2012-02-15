<?php
/** Value Object file for table unidad.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class Unidad extends VO
{
	/**
	  * Constructor de Unidad
	  * 
	  * Para construir un objeto de tipo Unidad debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Unidad
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_unidad']) ){
				$this->id_unidad = $data['id_unidad'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['es_entero']) ){
				$this->es_entero = $data['es_entero'];
			}
			if( isset($data['activa']) ){
				$this->activa = $data['activa'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Unidad en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_unidad" => $this->id_unidad,
			"nombre" => $this->nombre,
			"descripcion" => $this->descripcion,
			"es_entero" => $this->es_entero,
			"activa" => $this->activa
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_unidad
	  * 
	  * Id de la tabal unidad convertible<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_unidad;

	/**
	  * nombre
	  * 
	  * nombre de la unidad convertible<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $nombre;

	/**
	  * descripcion
	  * 
	  * Descripcion larga de esta unidad<br>
	  * @access public
	  * @var varchar(255)
	  */
	public $descripcion;

	/**
	  * es_entero
	  * 
	  * Si esta unidad manejara sus cantidades como enteras o como flotantes<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $es_entero;

	/**
	  * activa
	  * 
	  * Si esta unidad esa activa o no<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $activa;

	/**
	  * getIdUnidad
	  * 
	  * Get the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la tabal unidad convertible
	  * @return int(11)
	  */
	final public function getIdUnidad()
	{
		return $this->id_unidad;
	}

	/**
	  * setIdUnidad( $id_unidad )
	  * 
	  * Set the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la tabal unidad convertible.
	  * Una validacion basica se hara aqui para comprobar que <i>id_unidad</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdUnidad( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdUnidad( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdUnidad( $id_unidad )
	{
		$this->id_unidad = $id_unidad;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre de la unidad convertible
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es nombre de la unidad convertible.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga de esta unidad
	  * @return varchar(255)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga de esta unidad.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getEsEntero
	  * 
	  * Get the <i>es_entero</i> property for this object. Donde <i>es_entero</i> es Si esta unidad manejara sus cantidades como enteras o como flotantes
	  * @return tinyint(1)
	  */
	final public function getEsEntero()
	{
		return $this->es_entero;
	}

	/**
	  * setEsEntero( $es_entero )
	  * 
	  * Set the <i>es_entero</i> property for this object. Donde <i>es_entero</i> es Si esta unidad manejara sus cantidades como enteras o como flotantes.
	  * Una validacion basica se hara aqui para comprobar que <i>es_entero</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEsEntero( $es_entero )
	{
		$this->es_entero = $es_entero;
	}

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es Si esta unidad esa activa o no
	  * @return tinyint(1)
	  */
	final public function getActiva()
	{
		return $this->activa;
	}

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es Si esta unidad esa activa o no.
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActiva( $activa )
	{
		$this->activa = $activa;
	}

}
