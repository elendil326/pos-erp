<?php
/** Value Object file for table detalle_cotizacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class DetalleCotizacion extends VO
{
	/**
	  * Constructor de DetalleCotizacion
	  * 
	  * Para construir un objeto de tipo DetalleCotizacion debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleCotizacion
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_cotizacion = $data['id_cotizacion'];
			$this->id_producto = $data['id_producto'];
			$this->cantidad = $data['cantidad'];
			$this->precio = $data['precio'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleCotizacion en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_cotizacion" => $this->id_cotizacion,
		"id_producto" => $this->id_producto,
		"cantidad" => $this->cantidad,
		"precio" => $this->precio
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_cotizacion
	  * 
	  * id de la cotizacion<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cotizacion;

	/**
	  * id_producto
	  * 
	  * id del producto<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * 
	  * cantidad cotizado<br>
	  * @access protected
	  * @var float
	  */
	protected $cantidad;

	/**
	  * precio
	  * 
	  * precio al que cotizo el producto<br>
	  * @access protected
	  * @var float
	  */
	protected $precio;

	/**
	  * getIdCotizacion
	  * 
	  * Get the <i>id_cotizacion</i> property for this object. Donde <i>id_cotizacion</i> es id de la cotizacion
	  * @return int(11)
	  */
	final public function getIdCotizacion()
	{
		return $this->id_cotizacion;
	}

	/**
	  * setIdCotizacion( $id_cotizacion )
	  * 
	  * Set the <i>id_cotizacion</i> property for this object. Donde <i>id_cotizacion</i> es id de la cotizacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cotizacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCotizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCotizacion( $id_cotizacion )
	{
		$this->id_cotizacion = $id_cotizacion;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto.
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
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad cotizado
	  * @return float
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad cotizado.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que cotizo el producto
	  * @return float
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que cotizo el producto.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

}
