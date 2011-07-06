<?php
/** Value Object file for table detalle_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author caffeina
  * @access public
  * @package docs
  * 
  */

class DetalleVenta extends VO
{
	/**
	  * Constructor de DetalleVenta
	  * 
	  * Para construir un objeto de tipo DetalleVenta debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleVenta
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_venta']) ){
				$this->id_venta = $data['id_venta'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
			if( isset($data['cantidad_procesada']) ){
				$this->cantidad_procesada = $data['cantidad_procesada'];
			}
			if( isset($data['precio']) ){
				$this->precio = $data['precio'];
			}
			if( isset($data['precio_procesada']) ){
				$this->precio_procesada = $data['precio_procesada'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleVenta en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_venta" => $this->id_venta,
			"id_producto" => $this->id_producto,
			"cantidad" => $this->cantidad,
			"cantidad_procesada" => $this->cantidad_procesada,
			"precio" => $this->precio,
			"precio_procesada" => $this->precio_procesada,
			"descuento" => $this->descuento
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_venta
	  * 
	  * venta a que se referencia<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_venta;

	/**
	  * id_producto
	  * 
	  * producto de la venta<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * 
	  * cantidad que se vendio<br>
	  * @access protected
	  * @var float
	  */
	protected $cantidad;

	/**
	  * cantidad_procesada
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $cantidad_procesada;

	/**
	  * precio
	  * 
	  * precio al que se vendio<br>
	  * @access protected
	  * @var float
	  */
	protected $precio;

	/**
	  * precio_procesada
	  * 
	  * el precio de los articulos procesados en esta venta<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_procesada;

	/**
	  * descuento
	  * 
	  * indica cuanto producto original se va a descontar de ese producto en esa venta<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a que se referencia.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es producto de la venta.
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
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio
	  * @return float
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getCantidadProcesada
	  * 
	  * Get the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getCantidadProcesada()
	{
		return $this->cantidad_procesada;
	}

	/**
	  * setCantidadProcesada( $cantidad_procesada )
	  * 
	  * Set the <i>cantidad_procesada</i> property for this object. Donde <i>cantidad_procesada</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCantidadProcesada( $cantidad_procesada )
	{
		$this->cantidad_procesada = $cantidad_procesada;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio
	  * @return float
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

	/**
	  * getPrecioProcesada
	  * 
	  * Get the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta
	  * @return float
	  */
	final public function getPrecioProcesada()
	{
		return $this->precio_procesada;
	}

	/**
	  * setPrecioProcesada( $precio_procesada )
	  * 
	  * Set the <i>precio_procesada</i> property for this object. Donde <i>precio_procesada</i> es el precio de los articulos procesados en esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_procesada</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioProcesada( $precio_procesada )
	{
		$this->precio_procesada = $precio_procesada;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es indica cuanto producto original se va a descontar de ese producto en esa venta.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

}
