<?php
/** Value Object file for table impuesto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author someone@caffeina.mx
  * @access public
  * @package docs
  * 
  */

class Impuesto extends VO
{
	/**
	  * Constructor de Impuesto
	  * 
	  * Para construir un objeto de tipo Impuesto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Impuesto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
                    if(is_string($data))
                        $data = self::object_to_array(json_decode($data));


			if( isset($data['id_impuesto']) ){
				$this->id_impuesto = $data['id_impuesto'];
			}
			if( isset($data['monto_porcentaje']) ){
				$this->monto_porcentaje = $data['monto_porcentaje'];
			}
			if( isset($data['es_monto']) ){
				$this->es_monto = $data['es_monto'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Impuesto en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_impuesto" => $this->id_impuesto,
			"monto_porcentaje" => $this->monto_porcentaje,
			"es_monto" => $this->es_monto,
			"nombre" => $this->nombre,
			"descripcion" => $this->descripcion
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_impuesto
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_impuesto;

	/**
	  * monto_porcentaje
	  * 
	  * El monto o e lporcentaje correspondiente del impuesto<br>
	  * @access public
	  * @var float
	  */
	public $monto_porcentaje;

	/**
	  * es_monto
	  * 
	  * True si el valor del campo monto_porcentaje es un monto, false si es un porcentaje<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $es_monto;

	/**
	  * nombre
	  * 
	  * Nombre del impuesto<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $nombre;

	/**
	  * descripcion
	  * 
	  * Descripcion larga del impuesto<br>
	  * @access public
	  * @var varchar(255)
	  */
	public $descripcion;

	/**
	  * getIdImpuesto
	  * 
	  * Get the <i>id_impuesto</i> property for this object. Donde <i>id_impuesto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdImpuesto()
	{
		return $this->id_impuesto;
	}

	/**
	  * setIdImpuesto( $id_impuesto )
	  * 
	  * Set the <i>id_impuesto</i> property for this object. Donde <i>id_impuesto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_impuesto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdImpuesto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdImpuesto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdImpuesto( $id_impuesto )
	{
		$this->id_impuesto = $id_impuesto;
	}

	/**
	  * getMontoPorcentaje
	  * 
	  * Get the <i>monto_porcentaje</i> property for this object. Donde <i>monto_porcentaje</i> es El monto o e lporcentaje correspondiente del impuesto
	  * @return float
	  */
	final public function getMontoPorcentaje()
	{
		return $this->monto_porcentaje;
	}

	/**
	  * setMontoPorcentaje( $monto_porcentaje )
	  * 
	  * Set the <i>monto_porcentaje</i> property for this object. Donde <i>monto_porcentaje</i> es El monto o e lporcentaje correspondiente del impuesto.
	  * Una validacion basica se hara aqui para comprobar que <i>monto_porcentaje</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMontoPorcentaje( $monto_porcentaje )
	{
		$this->monto_porcentaje = $monto_porcentaje;
	}

	/**
	  * getEsMonto
	  * 
	  * Get the <i>es_monto</i> property for this object. Donde <i>es_monto</i> es True si el valor del campo monto_porcentaje es un monto, false si es un porcentaje
	  * @return tinyint(1)
	  */
	final public function getEsMonto()
	{
		return $this->es_monto;
	}

	/**
	  * setEsMonto( $es_monto )
	  * 
	  * Set the <i>es_monto</i> property for this object. Donde <i>es_monto</i> es True si el valor del campo monto_porcentaje es un monto, false si es un porcentaje.
	  * Una validacion basica se hara aqui para comprobar que <i>es_monto</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEsMonto( $es_monto )
	{
		$this->es_monto = $es_monto;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del impuesto
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es Nombre del impuesto.
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
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga del impuesto
	  * @return varchar(255)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga del impuesto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

}
