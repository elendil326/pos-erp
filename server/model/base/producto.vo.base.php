<?php
/** Value Object file for table producto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Manuel
  * @access public
  * @package docs
  * 
  */

class Producto extends VO
{
	/**
	  * Constructor de Producto
	  * 
	  * Para construir un objeto de tipo Producto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Producto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['compra_en_mostrador']) ){
				$this->compra_en_mostrador = $data['compra_en_mostrador'];
			}
			if( isset($data['metodo_costeo']) ){
				$this->metodo_costeo = $data['metodo_costeo'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['codigo_producto']) ){
				$this->codigo_producto = $data['codigo_producto'];
			}
			if( isset($data['nombre_producto']) ){
				$this->nombre_producto = $data['nombre_producto'];
			}
			if( isset($data['garantia']) ){
				$this->garantia = $data['garantia'];
			}
			if( isset($data['costo_estandar']) ){
				$this->costo_estandar = $data['costo_estandar'];
			}
			if( isset($data['control_de_existencia']) ){
				$this->control_de_existencia = $data['control_de_existencia'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['foto_del_producto']) ){
				$this->foto_del_producto = $data['foto_del_producto'];
			}
			if( isset($data['costo_extra_almacen']) ){
				$this->costo_extra_almacen = $data['costo_extra_almacen'];
			}
			if( isset($data['codigo_de_barras']) ){
				$this->codigo_de_barras = $data['codigo_de_barras'];
			}
			if( isset($data['peso_producto']) ){
				$this->peso_producto = $data['peso_producto'];
			}
			if( isset($data['id_unidad']) ){
				$this->id_unidad = $data['id_unidad'];
			}
			if( isset($data['precio']) ){
				$this->precio = $data['precio'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Producto en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_producto" => $this->id_producto,
			"compra_en_mostrador" => $this->compra_en_mostrador,
			"metodo_costeo" => $this->metodo_costeo,
			"activo" => $this->activo,
			"codigo_producto" => $this->codigo_producto,
			"nombre_producto" => $this->nombre_producto,
			"garantia" => $this->garantia,
			"costo_estandar" => $this->costo_estandar,
			"control_de_existencia" => $this->control_de_existencia,
			"descripcion" => $this->descripcion,
			"foto_del_producto" => $this->foto_del_producto,
			"costo_extra_almacen" => $this->costo_extra_almacen,
			"codigo_de_barras" => $this->codigo_de_barras,
			"peso_producto" => $this->peso_producto,
			"id_unidad" => $this->id_unidad,
			"precio" => $this->precio
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_producto
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_producto;

	/**
	  * compra_en_mostrador
	  * 
	  * Verdadero si el producto se puede comprar en mostrador<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $compra_en_mostrador;

	/**
	  * metodo_costeo
	  * 
	  * Si el precio se toma del precio base o del costo del producto<br>
	  * @access public
	  * @var enum('precio','costo')
	  */
	public $metodo_costeo;

	/**
	  * activo
	  * 
	  * Si el producto esta activo o no<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $activo;

	/**
	  * codigo_producto
	  * 
	  * Codigo interno del producto<br>
	  * @access public
	  * @var varchar(32)
	  */
	public $codigo_producto;

	/**
	  * nombre_producto
	  * 
	  * Nombre del producto<br>
	  * @access public
	  * @var varchar(32)
	  */
	public $nombre_producto;

	/**
	  * garantia
	  * 
	  * Si este producto cuenta con un numero de meses de garantia<br>
	  * @access public
	  * @var int(11)
	  */
	public $garantia;

	/**
	  * costo_estandar
	  * 
	  * Costo estandar del producto<br>
	  * @access public
	  * @var float
	  */
	public $costo_estandar;

	/**
	  * control_de_existencia
	  * 
	  * 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote<br>
	  * @access public
	  * @var int(11)
	  */
	public $control_de_existencia;

	/**
	  * descripcion
	  * 
	  * Descripcion del producto<br>
	  * @access public
	  * @var varchar(255)
	  */
	public $descripcion;

	/**
	  * foto_del_producto
	  * 
	  * Url a una foto de este producto<br>
	  * @access public
	  * @var varchar(100)
	  */
	public $foto_del_producto;

	/**
	  * costo_extra_almacen
	  * 
	  * Si este producto produce un costo extra en el almacen<br>
	  * @access public
	  * @var float
	  */
	public $costo_extra_almacen;

	/**
	  * codigo_de_barras
	  * 
	  * El codigo de barras de este producto<br>
	  * @access public
	  * @var varchar(30)
	  */
	public $codigo_de_barras;

	/**
	  * peso_producto
	  * 
	  * El peso de este producto en Kg<br>
	  * @access public
	  * @var float
	  */
	public $peso_producto;

	/**
	  * id_unidad
	  * 
	  * Id de la unidad en la que usualmente se maneja este producto<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_unidad;

	/**
	  * precio
	  * 
	  * El precio fijo del producto<br>
	  * @access public
	  * @var float
	  */
	public $precio;

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es  [Campo no documentado].
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
	  * getCompraEnMostrador
	  * 
	  * Get the <i>compra_en_mostrador</i> property for this object. Donde <i>compra_en_mostrador</i> es Verdadero si el producto se puede comprar en mostrador
	  * @return tinyint(1)
	  */
	final public function getCompraEnMostrador()
	{
		return $this->compra_en_mostrador;
	}

	/**
	  * setCompraEnMostrador( $compra_en_mostrador )
	  * 
	  * Set the <i>compra_en_mostrador</i> property for this object. Donde <i>compra_en_mostrador</i> es Verdadero si el producto se puede comprar en mostrador.
	  * Una validacion basica se hara aqui para comprobar que <i>compra_en_mostrador</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setCompraEnMostrador( $compra_en_mostrador )
	{
		$this->compra_en_mostrador = $compra_en_mostrador;
	}

	/**
	  * getMetodoCosteo
	  * 
	  * Get the <i>metodo_costeo</i> property for this object. Donde <i>metodo_costeo</i> es Si el precio se toma del precio base o del costo del producto
	  * @return enum('precio','costo')
	  */
	final public function getMetodoCosteo()
	{
		return $this->metodo_costeo;
	}

	/**
	  * setMetodoCosteo( $metodo_costeo )
	  * 
	  * Set the <i>metodo_costeo</i> property for this object. Donde <i>metodo_costeo</i> es Si el precio se toma del precio base o del costo del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>metodo_costeo</i> es de tipo <i>enum('precio','costo')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('precio','costo')
	  */
	final public function setMetodoCosteo( $metodo_costeo )
	{
		$this->metodo_costeo = $metodo_costeo;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es Si el producto esta activo o no
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es Si el producto esta activo o no.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getCodigoProducto
	  * 
	  * Get the <i>codigo_producto</i> property for this object. Donde <i>codigo_producto</i> es Codigo interno del producto
	  * @return varchar(32)
	  */
	final public function getCodigoProducto()
	{
		return $this->codigo_producto;
	}

	/**
	  * setCodigoProducto( $codigo_producto )
	  * 
	  * Set the <i>codigo_producto</i> property for this object. Donde <i>codigo_producto</i> es Codigo interno del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_producto</i> es de tipo <i>varchar(32)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(32)
	  */
	final public function setCodigoProducto( $codigo_producto )
	{
		$this->codigo_producto = $codigo_producto;
	}

	/**
	  * getNombreProducto
	  * 
	  * Get the <i>nombre_producto</i> property for this object. Donde <i>nombre_producto</i> es Nombre del producto
	  * @return varchar(32)
	  */
	final public function getNombreProducto()
	{
		return $this->nombre_producto;
	}

	/**
	  * setNombreProducto( $nombre_producto )
	  * 
	  * Set the <i>nombre_producto</i> property for this object. Donde <i>nombre_producto</i> es Nombre del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre_producto</i> es de tipo <i>varchar(32)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(32)
	  */
	final public function setNombreProducto( $nombre_producto )
	{
		$this->nombre_producto = $nombre_producto;
	}

	/**
	  * getGarantia
	  * 
	  * Get the <i>garantia</i> property for this object. Donde <i>garantia</i> es Si este producto cuenta con un numero de meses de garantia
	  * @return int(11)
	  */
	final public function getGarantia()
	{
		return $this->garantia;
	}

	/**
	  * setGarantia( $garantia )
	  * 
	  * Set the <i>garantia</i> property for this object. Donde <i>garantia</i> es Si este producto cuenta con un numero de meses de garantia.
	  * Una validacion basica se hara aqui para comprobar que <i>garantia</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setGarantia( $garantia )
	{
		$this->garantia = $garantia;
	}

	/**
	  * getCostoEstandar
	  * 
	  * Get the <i>costo_estandar</i> property for this object. Donde <i>costo_estandar</i> es Costo estandar del producto
	  * @return float
	  */
	final public function getCostoEstandar()
	{
		return $this->costo_estandar;
	}

	/**
	  * setCostoEstandar( $costo_estandar )
	  * 
	  * Set the <i>costo_estandar</i> property for this object. Donde <i>costo_estandar</i> es Costo estandar del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>costo_estandar</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCostoEstandar( $costo_estandar )
	{
		$this->costo_estandar = $costo_estandar;
	}

	/**
	  * getControlDeExistencia
	  * 
	  * Get the <i>control_de_existencia</i> property for this object. Donde <i>control_de_existencia</i> es 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
	  * @return int(11)
	  */
	final public function getControlDeExistencia()
	{
		return $this->control_de_existencia;
	}

	/**
	  * setControlDeExistencia( $control_de_existencia )
	  * 
	  * Set the <i>control_de_existencia</i> property for this object. Donde <i>control_de_existencia</i> es 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote.
	  * Una validacion basica se hara aqui para comprobar que <i>control_de_existencia</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setControlDeExistencia( $control_de_existencia )
	{
		$this->control_de_existencia = $control_de_existencia;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion del producto
	  * @return varchar(255)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getFotoDelProducto
	  * 
	  * Get the <i>foto_del_producto</i> property for this object. Donde <i>foto_del_producto</i> es Url a una foto de este producto
	  * @return varchar(100)
	  */
	final public function getFotoDelProducto()
	{
		return $this->foto_del_producto;
	}

	/**
	  * setFotoDelProducto( $foto_del_producto )
	  * 
	  * Set the <i>foto_del_producto</i> property for this object. Donde <i>foto_del_producto</i> es Url a una foto de este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>foto_del_producto</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setFotoDelProducto( $foto_del_producto )
	{
		$this->foto_del_producto = $foto_del_producto;
	}

	/**
	  * getCostoExtraAlmacen
	  * 
	  * Get the <i>costo_extra_almacen</i> property for this object. Donde <i>costo_extra_almacen</i> es Si este producto produce un costo extra en el almacen
	  * @return float
	  */
	final public function getCostoExtraAlmacen()
	{
		return $this->costo_extra_almacen;
	}

	/**
	  * setCostoExtraAlmacen( $costo_extra_almacen )
	  * 
	  * Set the <i>costo_extra_almacen</i> property for this object. Donde <i>costo_extra_almacen</i> es Si este producto produce un costo extra en el almacen.
	  * Una validacion basica se hara aqui para comprobar que <i>costo_extra_almacen</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCostoExtraAlmacen( $costo_extra_almacen )
	{
		$this->costo_extra_almacen = $costo_extra_almacen;
	}

	/**
	  * getCodigoDeBarras
	  * 
	  * Get the <i>codigo_de_barras</i> property for this object. Donde <i>codigo_de_barras</i> es El codigo de barras de este producto
	  * @return varchar(30)
	  */
	final public function getCodigoDeBarras()
	{
		return $this->codigo_de_barras;
	}

	/**
	  * setCodigoDeBarras( $codigo_de_barras )
	  * 
	  * Set the <i>codigo_de_barras</i> property for this object. Donde <i>codigo_de_barras</i> es El codigo de barras de este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>codigo_de_barras</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	final public function setCodigoDeBarras( $codigo_de_barras )
	{
		$this->codigo_de_barras = $codigo_de_barras;
	}

	/**
	  * getPesoProducto
	  * 
	  * Get the <i>peso_producto</i> property for this object. Donde <i>peso_producto</i> es El peso de este producto en Kg
	  * @return float
	  */
	final public function getPesoProducto()
	{
		return $this->peso_producto;
	}

	/**
	  * setPesoProducto( $peso_producto )
	  * 
	  * Set the <i>peso_producto</i> property for this object. Donde <i>peso_producto</i> es El peso de este producto en Kg.
	  * Una validacion basica se hara aqui para comprobar que <i>peso_producto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPesoProducto( $peso_producto )
	{
		$this->peso_producto = $peso_producto;
	}

	/**
	  * getIdUnidad
	  * 
	  * Get the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la unidad en la que usualmente se maneja este producto
	  * @return int(11)
	  */
	final public function getIdUnidad()
	{
		return $this->id_unidad;
	}

	/**
	  * setIdUnidad( $id_unidad )
	  * 
	  * Set the <i>id_unidad</i> property for this object. Donde <i>id_unidad</i> es Id de la unidad en la que usualmente se maneja este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_unidad</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUnidad( $id_unidad )
	{
		$this->id_unidad = $id_unidad;
	}

	/**
	  * getPrecio
	  * 
	  * Get the <i>precio</i> property for this object. Donde <i>precio</i> es El precio fijo del producto
	  * @return float
	  */
	final public function getPrecio()
	{
		return $this->precio;
	}

	/**
	  * setPrecio( $precio )
	  * 
	  * Set the <i>precio</i> property for this object. Donde <i>precio</i> es El precio fijo del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>precio</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecio( $precio )
	{
		$this->precio = $precio;
	}

}
