<?php
/** Value Object file for table precio_producto_rol.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class PrecioProductoRol extends VO
{
	/**
	  * Constructor de PrecioProductoRol
	  * 
	  * Para construir un objeto de tipo PrecioProductoRol debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PrecioProductoRol
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['id_rol']) ){
				$this->id_rol = $data['id_rol'];
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
	  * Este metodo permite tratar a un objeto PrecioProductoRol en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_producto" => $this->id_producto,
			"id_rol" => $this->id_rol,
			"precio_utilidad" => $this->precio_utilidad,
			"es_margen_utilidad" => $this->es_margen_utilidad
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_producto
	  * 
	  * Id del producto al que se le aplicara un precio de acuerdo al cliente<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * id_rol
	  * 
	  * Id del rol al que se le ofrecera el producto a un cierto precio<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_rol;

	/**
	  * precio_utilidad
	  * 
	  * Precio o porcentaje del margen de utilidad que se le ganara a este producto para este rol<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_utilidad;

	/**
	  * es_margen_utilidad
	  * 
	  * Verdadero si el valor del campo precio_utilidad es un margen de utilidad, false si es un precio fijo<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $es_margen_utilidad;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es Id del producto al que se le aplicara un precio de acuerdo al cliente
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es Id del producto al que se le aplicara un precio de acuerdo al cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getIdRol
	  * 
	  * Get the <i>id_rol</i> property for this object. Donde <i>id_rol</i> es Id del rol al que se le ofrecera el producto a un cierto precio
	  * @return int(11)
	  */
	final public function getIdRol()
	{
		return $this->id_rol;
	}

	/**
	  * setIdRol( $id_rol )
	  * 
	  * Set the <i>id_rol</i> property for this object. Donde <i>id_rol</i> es Id del rol al que se le ofrecera el producto a un cierto precio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_rol</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdRol( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdRol( $id_rol )
	{
		$this->id_rol = $id_rol;
	}

	/**
	  * getPrecioUtilidad
	  * 
	  * Get the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje del margen de utilidad que se le ganara a este producto para este rol
	  * @return float
	  */
	final public function getPrecioUtilidad()
	{
		return $this->precio_utilidad;
	}

	/**
	  * setPrecioUtilidad( $precio_utilidad )
	  * 
	  * Set the <i>precio_utilidad</i> property for this object. Donde <i>precio_utilidad</i> es Precio o porcentaje del margen de utilidad que se le ganara a este producto para este rol.
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
