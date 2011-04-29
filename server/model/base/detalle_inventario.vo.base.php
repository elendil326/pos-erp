<?php
/** Value Object file for table detalle_inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez
  * @access public
  * @package docs
  * 
  */

class DetalleInventario extends VO
{
	/**
	  * Constructor de DetalleInventario
	  * 
	  * Para construir un objeto de tipo DetalleInventario debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleInventario
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['precio_venta']) ){
				$this->precio_venta = $data['precio_venta'];
			}
			if( isset($data['existencias']) ){
				$this->existencias = $data['existencias'];
			}
			if( isset($data['existencias_procesadas']) ){
				$this->existencias_procesadas = $data['existencias_procesadas'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleInventario en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_producto" => $this->id_producto,
			"id_sucursal" => $this->id_sucursal,
			"precio_venta" => $this->precio_venta,
			"existencias" => $this->existencias,
			"existencias_procesadas" => $this->existencias_procesadas
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_producto
	  * 
	  * id del producto al que se refiere<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * id_sucursal
	  * 
	  * id de la sucursal<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * precio_venta
	  * 
	  * precio al que se vendera al publico<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_venta;

	/**
	  * existencias
	  * 
	  * cantidad de producto que hay actualmente en almacen de esta sucursal<br>
	  * @access protected
	  * @var float
	  */
	protected $existencias;

	/**
	  * existencias_procesadas
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $existencias_procesadas;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto al que se refiere
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es id del producto al que se refiere.
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
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es id de la sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSucursal( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico
	  * @return float
	  */
	final public function getPrecioVenta()
	{
		return $this->precio_venta;
	}

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es precio al que se vendera al publico.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioVenta( $precio_venta )
	{
		$this->precio_venta = $precio_venta;
	}

	/**
	  * getExistencias
	  * 
	  * Get the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal
	  * @return float
	  */
	final public function getExistencias()
	{
		return $this->existencias;
	}

	/**
	  * setExistencias( $existencias )
	  * 
	  * Set the <i>existencias</i> property for this object. Donde <i>existencias</i> es cantidad de producto que hay actualmente en almacen de esta sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>existencias</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setExistencias( $existencias )
	{
		$this->existencias = $existencias;
	}

	/**
	  * getExistenciasProcesadas
	  * 
	  * Get the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getExistenciasProcesadas()
	{
		return $this->existencias_procesadas;
	}

	/**
	  * setExistenciasProcesadas( $existencias_procesadas )
	  * 
	  * Set the <i>existencias_procesadas</i> property for this object. Donde <i>existencias_procesadas</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>existencias_procesadas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setExistenciasProcesadas( $existencias_procesadas )
	{
		$this->existencias_procesadas = $existencias_procesadas;
	}

}
