<?php
/** Value Object file for table compra_proveedor_fragmentacion.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class CompraProveedorFragmentacion extends VO
{
	/**
	  * Constructor de CompraProveedorFragmentacion
	  * 
	  * Para construir un objeto de tipo CompraProveedorFragmentacion debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CompraProveedorFragmentacion
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_fragmentacion']) ){
				$this->id_fragmentacion = $data['id_fragmentacion'];
			}
			if( isset($data['id_compra_proveedor']) ){
				$this->id_compra_proveedor = $data['id_compra_proveedor'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
			if( isset($data['procesada']) ){
				$this->procesada = $data['procesada'];
			}
			if( isset($data['precio']) ){
				$this->precio = $data['precio'];
			}
			if( isset($data['descripcion_ref_id']) ){
				$this->descripcion_ref_id = $data['descripcion_ref_id'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CompraProveedorFragmentacion en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_fragmentacion" => $this->id_fragmentacion,
			"id_compra_proveedor" => $this->id_compra_proveedor,
			"id_producto" => $this->id_producto,
			"fecha" => $this->fecha,
			"descripcion" => $this->descripcion,
			"cantidad" => $this->cantidad,
			"procesada" => $this->procesada,
			"precio" => $this->precio,
			"descripcion_ref_id" => $this->descripcion_ref_id
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_fragmentacion
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_fragmentacion;

	/**
	  * id_compra_proveedor
	  * 
	  * La compra a proveedor del producto<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra_proveedor;

	/**
	  * id_producto
	  * 
	  * El id del producto<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * fecha
	  * 
	  * la fecha de esta operacion<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * descripcion
	  * 
	  * la descripcion de lo que ha sucedido, vendido, surtido, basura... etc.<br>
	  * @access protected
	  * @var varchar(16)
	  */
	protected $descripcion;

	/**
	  * cantidad
	  * 
	  * cuanto fue consumido o agregado !!! en la escala que se tiene de este prod<br>
	  * @access protected
	  * @var double
	  */
	protected $cantidad;

	/**
	  * procesada
	  * 
	  * si estamos hablando de producto procesado, debera ser true<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $procesada;

	/**
	  * precio
	  * 
	  * a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $precio;

	/**
	  * descripcion_ref_id
	  * 
	  * si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc..<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $descripcion_ref_id;

	/**
	  * getIdFragmentacion
	  * 
	  * Get the <i>id_fragmentacion</i> property for this object. Donde <i>id_fragmentacion</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdFragmentacion()
	{
		return $this->id_fragmentacion;
	}

	/**
	  * setIdFragmentacion( $id_fragmentacion )
	  * 
	  * Set the <i>id_fragmentacion</i> property for this object. Donde <i>id_fragmentacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_fragmentacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdFragmentacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdFragmentacion( $id_fragmentacion )
	{
		$this->id_fragmentacion = $id_fragmentacion;
	}

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es La compra a proveedor del producto
	  * @return int(11)
	  */
	final public function getIdCompraProveedor()
	{
		return $this->id_compra_proveedor;
	}

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es La compra a proveedor del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCompraProveedor( $id_compra_proveedor )
	{
		$this->id_compra_proveedor = $id_compra_proveedor;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es El id del producto
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es El id del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es la fecha de esta operacion
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es la fecha de esta operacion.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es la descripcion de lo que ha sucedido, vendido, surtido, basura... etc.
	  * @return varchar(16)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es la descripcion de lo que ha sucedido, vendido, surtido, basura... etc..
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cuanto fue consumido o agregado !!! en la escala que se tiene de este prod
	  * @return double
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cuanto fue consumido o agregado !!! en la escala que se tiene de este prod.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>double</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param double
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getProcesada
	  * 
	  * Get the <i>procesada</i> property for this object. Donde <i>procesada</i> es si estamos hablando de producto procesado, debera ser true
	  * @return tinyint(1)
	  */
	final public function getProcesada()
	{
		return $this->procesada;
	}

	/**
	  * setProcesada( $procesada )
	  * 
	  * Set the <i>procesada</i> property for this object. Donde <i>procesada</i> es si estamos hablando de producto procesado, debera ser true.
	  * Una validacion basica se hara aqui para comprobar que <i>procesada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setProcesada( $procesada )
	{
		$this->procesada = $procesada;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo
	  * @return int(11)
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es a cuanto se vendio esta porcion del producto, si es el resultado de algun proceso sera 0 por ejemplo.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

	/**
	  * getDescripcionRefId
	  * 
	  * Get the <i>descripcion_ref_id</i> property for this object. Donde <i>descripcion_ref_id</i> es si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc..
	  * @return int(11)
	  */
	final public function getDescripcionRefId()
	{
		return $this->descripcion_ref_id;
	}

	/**
	  * setDescripcionRefId( $descripcion_ref_id )
	  * 
	  * Set the <i>descripcion_ref_id</i> property for this object. Donde <i>descripcion_ref_id</i> es si se refiere a una venta, se puede poner el id de esa venta, si fue de surtir, el id de la compra, etc...
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion_ref_id</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setDescripcionRefId( $descripcion_ref_id )
	{
		$this->descripcion_ref_id = $descripcion_ref_id;
	}

}
