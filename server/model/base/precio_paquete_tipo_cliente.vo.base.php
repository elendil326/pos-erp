<?php
/** Value Object file for table precio_paquete_tipo_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class PrecioPaqueteTipoCliente extends VO
{
	/**
	  * Constructor de PrecioPaqueteTipoCliente
	  * 
	  * Para construir un objeto de tipo PrecioPaqueteTipoCliente debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PrecioPaqueteTipoCliente
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_paquete']) ){
				$this->id_paquete = $data['id_paquete'];
			}
			if( isset($data['id_tipo_cliente']) ){
				$this->id_tipo_cliente = $data['id_tipo_cliente'];
			}
			if( isset($data['precio_utilidad']) ){
				$this->precio_utilidad = $data['precio_utilidad'];
			}
			if( isset($data['es_margen_utilidad']) ){
				$this->es_margen_utilidad = $data['es_margen_utilidad'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PrecioPaqueteTipoCliente en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_paquete" => $this->id_paquete,
			"id_tipo_cliente" => $this->id_tipo_cliente,
			"precio_utilidad" => $this->precio_utilidad,
			"es_margen_utilidad" => $this->es_margen_utilidad
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_paquete
	  * 
	  * Id del paquete al que se le aplicara un precio de acuerdo al rol<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_paquete;

	/**
	  * id_tipo_cliente
	  * 
	  * Id del tipo de cliente al que se le ofrecera el paquete a un cierto precio<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_tipo_cliente;

	/**
	  * precio_utilidad
	  * 
	  * Precio o porcentaje del margen de utilidad que se le ganara a este paquete para este tipo de cliente<br>
	  * @access public
	  * @var float
	  */
	public $precio_utilidad;

	/**
	  * es_margen_utilidad
	  * 
	  * Verdadero si el valor del campo precio_utilidad es un margen de utilidad, false si es un precio fijo<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $es_margen_utilidad;

	/**
	  * getIdPaquete
	  * 
	  * Get the <i>id_paquete</i> property for this object. Donde <i>id_paquete</i> es Id del paquete al que se le aplicara un precio de acuerdo al rol
	  * @return int(11)
	  */
	final public function getIdPaquete()
	{
		return $this->id_paquete;
	}

	/**
	  * setIdPaquete( $id_paquete )
	  * 
	  * Set the <i>id_paquete</i> property for this object. Donde <i>id_paquete</i> es Id del paquete al que se le aplicara un precio de acuerdo al rol.
	  * Una validacion basica se hara aqui para comprobar que <i>id_paquete</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPaquete( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPaquete( $id_paquete )
	{
		$this->id_paquete = $id_paquete;
	}

	/**
	  * getIdTipoCliente
	  * 
	  * Get the <i>id_tipo_cliente</i> property for this object. Donde <i>id_tipo_cliente</i> es Id del tipo de cliente al que se le ofrecera el paquete a un cierto precio
	  * @return int(11)
	  */
	final public function getIdTipoCliente()
	{
		return $this->id_tipo_cliente;
	}

	/**
	  * setIdTipoCliente( $id_tipo_cliente )
	  * 
	  * Set the <i>id_tipo_cliente</i> property for this object. Donde <i>id_tipo_cliente</i> es Id del tipo de cliente al que se le ofrecera el paquete a un cierto precio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_tipo_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdTipoCliente( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdTipoCliente( $id_tipo_cliente )
	{
		$this->id_tipo_cliente = $id_tipo_cliente;
	}

	/**
	  * getPrecioUtilidad
	  * 
	  * Get the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje del margen de utilidad que se le ganara a este paquete para este tipo de cliente
	  * @return float
	  */
	final public function getPrecioUtilidad()
	{
		return $this->precio_utilidad;
	}

	/**
	  * setPrecioUtilidad( $precio_utilidad )
	  * 
	  * Set the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje del margen de utilidad que se le ganara a este paquete para este tipo de cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_utilidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioUtilidad( $precio_utilidad )
	{
		$this->precio_utilidad = $precio_utilidad;
	}

	/**
	  * getEsMargenUtilidad
	  * 
	  * Get the <i>es_margen_utilidad</i> property for this object. Donde <i>es_margen_utilidad</i> es Verdadero si el valor del campo precio_utilidad es un margen de utilidad, false si es un precio fijo
	  * @return tinyint(1)
	  */
	final public function getEsMargenUtilidad()
	{
		return $this->es_margen_utilidad;
	}

	/**
	  * setEsMargenUtilidad( $es_margen_utilidad )
	  * 
	  * Set the <i>es_margen_utilidad</i> property for this object. Donde <i>es_margen_utilidad</i> es Verdadero si el valor del campo precio_utilidad es un margen de utilidad, false si es un precio fijo.
	  * Una validacion basica se hara aqui para comprobar que <i>es_margen_utilidad</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEsMargenUtilidad( $es_margen_utilidad )
	{
		$this->es_margen_utilidad = $es_margen_utilidad;
	}

}
