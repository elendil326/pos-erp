<?php
/** Value Object file for table productos_proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class ProductosProveedor extends VO
{
	/**
	  * Constructor de ProductosProveedor
	  * 
	  * Para construir un objeto de tipo ProductosProveedor debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ProductosProveedor
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['clave_producto']) ){
				$this->clave_producto = $data['clave_producto'];
			}
			if( isset($data['id_proveedor']) ){
				$this->id_proveedor = $data['id_proveedor'];
			}
			if( isset($data['id_inventario']) ){
				$this->id_inventario = $data['id_inventario'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['precio']) ){
				$this->precio = $data['precio'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto ProductosProveedor en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_producto" => $this->id_producto,
		"clave_producto" => $this->clave_producto,
		"id_proveedor" => $this->id_proveedor,
		"id_inventario" => $this->id_inventario,
		"descripcion" => $this->descripcion,
		"precio" => $this->precio
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_producto
	  * 
	  * identificador del producto<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * clave_producto
	  * 
	  * clave de producto para el proveedor<br>
	  * @access protected
	  * @var varchar(20)
	  */
	protected $clave_producto;

	/**
	  * id_proveedor
	  * 
	  * clave del proveedor<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_proveedor;

	/**
	  * id_inventario
	  * 
	  * clave con la que entra a nuestro inventario<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_inventario;

	/**
	  * descripcion
	  * 
	  * Descripcion del producto que nos vende el proveedor<br>
	  * @access protected
	  * @var varchar(200)
	  */
	protected $descripcion;

	/**
	  * precio
	  * 
	  * precio al que se compra el producto (sin descuento)<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $precio;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del producto
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es identificador del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getClaveProducto
	  * 
	  * Get the <i>clave_producto</i> property for this object. Donde <i>clave_producto</i> es clave de producto para el proveedor
	  * @return varchar(20)
	  */
	final public function getClaveProducto()
	{
		return $this->clave_producto;
	}

	/**
	  * setClaveProducto( $clave_producto )
	  * 
	  * Set the <i>clave_producto</i> property for this object. Donde <i>clave_producto</i> es clave de producto para el proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>clave_producto</i> es de tipo <i>varchar(20)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(20)
	  */
	final public function setClaveProducto( $clave_producto )
	{
		$this->clave_producto = $clave_producto;
	}

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es clave del proveedor
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es clave del proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * getIdInventario
	  * 
	  * Get the <i>id_inventario</i> property for this object. Donde <i>id_inventario</i> es clave con la que entra a nuestro inventario
	  * @return int(11)
	  */
	final public function getIdInventario()
	{
		return $this->id_inventario;
	}

	/**
	  * setIdInventario( $id_inventario )
	  * 
	  * Set the <i>id_inventario</i> property for this object. Donde <i>id_inventario</i> es clave con la que entra a nuestro inventario.
	  * Una validacion basica se hara aqui para comprobar que <i>id_inventario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdInventario( $id_inventario )
	{
		$this->id_inventario = $id_inventario;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion del producto que nos vende el proveedor
	  * @return varchar(200)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion del producto que nos vende el proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(200)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(200)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compra el producto (sin descuento)
	  * @return int(11)
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es precio al que se compra el producto (sin descuento).
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

}
