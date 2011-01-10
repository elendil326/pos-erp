<?php
/** Value Object file for table detalle_compra_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class DetalleCompraSucursal extends VO
{
	/**
	  * Constructor de DetalleCompraSucursal
	  * 
	  * Para construir un objeto de tipo DetalleCompraSucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleCompraSucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra']) ){
				$this->id_compra = $data['id_compra'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
			if( isset($data['precio']) ){
				$this->precio = $data['precio'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
			if( isset($data['procesadas']) ){
				$this->procesadas = $data['procesadas'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleCompraSucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra" => $this->id_compra,
			"id_producto" => $this->id_producto,
			"cantidad" => $this->cantidad,
			"precio" => $this->precio,
			"descuento" => $this->descuento,
			"procesadas" => $this->procesadas
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_compra
	  * 
	  * id de la compra<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra;

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
	  * cantidad comprada<br>
	  * @access protected
	  * @var float
	  */
	protected $cantidad;

	/**
	  * precio
	  * 
	  * costo de compra<br>
	  * @access protected
	  * @var float
	  */
	protected $precio;

	/**
	  * descuento
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $descuento;

	/**
	  * procesadas
	  * 
	  * verdadero si este detalle se refiere a compras procesadas (limpias)<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $procesadas;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
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
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad comprada
	  * @return float
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad comprada.
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
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es costo de compra
	  * @return float
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es costo de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

	/**
	  * getProcesadas
	  * 
	  * Get the <i>procesadas</i> property for this object. Donde <i>procesadas</i> es verdadero si este detalle se refiere a compras procesadas (limpias)
	  * @return tinyint(1)
	  */
	final public function getProcesadas()
	{
		return $this->procesadas;
	}

	/**
	  * setProcesadas( $procesadas )
	  * 
	  * Set the <i>procesadas</i> property for this object. Donde <i>procesadas</i> es verdadero si este detalle se refiere a compras procesadas (limpias).
	  * Una validacion basica se hara aqui para comprobar que <i>procesadas</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setProcesadas( $procesadas )
	{
		$this->procesadas = $procesadas;
	}

}
