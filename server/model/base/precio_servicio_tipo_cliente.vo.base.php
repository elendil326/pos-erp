<?php
/** Value Object file for table precio_servicio_tipo_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class PrecioServicioTipoCliente extends VO
{
	/**
	  * Constructor de PrecioServicioTipoCliente
	  * 
	  * Para construir un objeto de tipo PrecioServicioTipoCliente debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PrecioServicioTipoCliente
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_servicio']) ){
				$this->id_servicio = $data['id_servicio'];
			}
			if( isset($data['id_tipo_cliente']) ){
				$this->id_tipo_cliente = $data['id_tipo_cliente'];
			}
			if( isset($data['es_margen_utilidad']) ){
				$this->es_margen_utilidad = $data['es_margen_utilidad'];
			}
			if( isset($data['precio_utilidad']) ){
				$this->precio_utilidad = $data['precio_utilidad'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PrecioServicioTipoCliente en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_servicio" => $this->id_servicio,
			"id_tipo_cliente" => $this->id_tipo_cliente,
			"es_margen_utilidad" => $this->es_margen_utilidad,
			"precio_utilidad" => $this->precio_utilidad
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_servicio
	  * 
	  * Id del servicio al que se le aplicara un precio de acuerdo al cliente<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_servicio;

	/**
	  * id_tipo_cliente
	  * 
	  * Id del tipo de cliente al que se le ofrecera el servicio a un cierto precio<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_tipo_cliente;

	/**
	  * es_margen_utilidad
	  * 
	  * Verdadero si el valor del campo precio_utilidad es un margen de utilidad, false si es un precio fijo<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $es_margen_utilidad;

	/**
	  * precio_utilidad
	  * 
	  * Precio o porcentaje de margen de utilidad que se le ganara a este servicio al venderle a este tipo de cliente<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_utilidad;

	/**
	  * getIdServicio
	  * 
	  * Get the <i>id_servicio</i> property for this object. Donde <i>id_servicio</i> es Id del servicio al que se le aplicara un precio de acuerdo al cliente
	  * @return int(11)
	  */
	final public function getIdServicio()
	{
		return $this->id_servicio;
	}

	/**
	  * setIdServicio( $id_servicio )
	  * 
	  * Set the <i>id_servicio</i> property for this object. Donde <i>id_servicio</i> es Id del servicio al que se le aplicara un precio de acuerdo al cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_servicio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdServicio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdServicio( $id_servicio )
	{
		$this->id_servicio = $id_servicio;
	}

	/**
	  * getIdTipoCliente
	  * 
	  * Get the <i>id_tipo_cliente</i> property for this object. Donde <i>id_tipo_cliente</i> es Id del tipo de cliente al que se le ofrecera el servicio a un cierto precio
	  * @return int(11)
	  */
	final public function getIdTipoCliente()
	{
		return $this->id_tipo_cliente;
	}

	/**
	  * setIdTipoCliente( $id_tipo_cliente )
	  * 
	  * Set the <i>id_tipo_cliente</i> property for this object. Donde <i>id_tipo_cliente</i> es Id del tipo de cliente al que se le ofrecera el servicio a un cierto precio.
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

	/**
	  * getPrecioUtilidad
	  * 
	  * Get the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje de margen de utilidad que se le ganara a este servicio al venderle a este tipo de cliente
	  * @return float
	  */
	final public function getPrecioUtilidad()
	{
		return $this->precio_utilidad;
	}

	/**
	  * setPrecioUtilidad( $precio_utilidad )
	  * 
	  * Set the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje de margen de utilidad que se le ganara a este servicio al venderle a este tipo de cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_utilidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioUtilidad( $precio_utilidad )
	{
		$this->precio_utilidad = $precio_utilidad;
	}

}
