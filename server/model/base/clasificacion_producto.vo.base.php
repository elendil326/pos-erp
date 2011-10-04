<?php
/** Value Object file for table clasificacion_producto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class ClasificacionProducto extends VO
{
	/**
	  * Constructor de ClasificacionProducto
	  * 
	  * Para construir un objeto de tipo ClasificacionProducto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ClasificacionProducto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_clasificacion_producto']) ){
				$this->id_clasificacion_producto = $data['id_clasificacion_producto'];
			}
			if( isset($data['nombre']) ){
				$this->nombre = $data['nombre'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['garantia']) ){
				$this->garantia = $data['garantia'];
			}
			if( isset($data['activa']) ){
				$this->activa = $data['activa'];
			}
			if( isset($data['margen_utilidad']) ){
				$this->margen_utilidad = $data['margen_utilidad'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto ClasificacionProducto en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_clasificacion_producto" => $this->id_clasificacion_producto,
			"nombre" => $this->nombre,
			"descripcion" => $this->descripcion,
			"garantia" => $this->garantia,
			"activa" => $this->activa,
			"margen_utilidad" => $this->margen_utilidad,
			"descuento" => $this->descuento
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_clasificacion_producto
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_clasificacion_producto;

	/**
	  * nombre
	  * 
	  * el nombre de esta clasificacion<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $nombre;

	/**
	  * descripcion
	  * 
	  * Descripcion larga de la clasificacion del producto<br>
	  * @access protected
	  * @var varchar(255)
	  */
	protected $descripcion;

	/**
	  * garantia
	  * 
	  * numero de meses que tendran los productos de esta clasificacion<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $garantia;

	/**
	  * activa
	  * 
	  * Si esta claificacion esta activa<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activa;

	/**
	  * margen_utilidad
	  * 
	  * Margen de utilidad que aplicara a todos los productos de esta clasificacion de productos<br>
	  * @access protected
	  * @var float
	  */
	protected $margen_utilidad;

	/**
	  * descuento
	  * 
	  * Descuento que se apicar a esta clasificaciond e producto<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * getIdClasificacionProducto
	  * 
	  * Get the <i>id_clasificacion_producto</i> property for this object. Donde <i>id_clasificacion_producto</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdClasificacionProducto()
	{
		return $this->id_clasificacion_producto;
	}

	/**
	  * setIdClasificacionProducto( $id_clasificacion_producto )
	  * 
	  * Set the <i>id_clasificacion_producto</i> property for this object. Donde <i>id_clasificacion_producto</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_clasificacion_producto</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdClasificacionProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdClasificacionProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdClasificacionProducto( $id_clasificacion_producto )
	{
		$this->id_clasificacion_producto = $id_clasificacion_producto;
	}

	/**
	  * getNombre
	  * 
	  * Get the <i>nombre</i> property for this object. Donde <i>nombre</i> es el nombre de esta clasificacion
	  * @return varchar(64)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * setNombre( $nombre )
	  * 
	  * Set the <i>nombre</i> property for this object. Donde <i>nombre</i> es el nombre de esta clasificacion.
	  * Una validacion basica se hara aqui para comprobar que <i>nombre</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga de la clasificacion del producto
	  * @return varchar(255)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es Descripcion larga de la clasificacion del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getGarantia
	  * 
	  * Get the <i>garantia</i> property for this object. Donde <i>garantia</i> es numero de meses que tendran los productos de esta clasificacion
	  * @return int(11)
	  */
	final public function getGarantia()
	{
		return $this->garantia;
	}

	/**
	  * setGarantia( $garantia )
	  * 
	  * Set the <i>garantia</i> property for this object. Donde <i>garantia</i> es numero de meses que tendran los productos de esta clasificacion.
	  * Una validacion basica se hara aqui para comprobar que <i>garantia</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setGarantia( $garantia )
	{
		$this->garantia = $garantia;
	}

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es Si esta claificacion esta activa
	  * @return tinyint(1)
	  */
	final public function getActiva()
	{
		return $this->activa;
	}

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es Si esta claificacion esta activa.
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActiva( $activa )
	{
		$this->activa = $activa;
	}

	/**
	  * getMargenUtilidad
	  * 
	  * Get the <i>margen_utilidad</i> property for this object. Donde <i>margen_utilidad</i> es Margen de utilidad que aplicara a todos los productos de esta clasificacion de productos
	  * @return float
	  */
	final public function getMargenUtilidad()
	{
		return $this->margen_utilidad;
	}

	/**
	  * setMargenUtilidad( $margen_utilidad )
	  * 
	  * Set the <i>margen_utilidad</i> property for this object. Donde <i>margen_utilidad</i> es Margen de utilidad que aplicara a todos los productos de esta clasificacion de productos.
	  * Una validacion basica se hara aqui para comprobar que <i>margen_utilidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMargenUtilidad( $margen_utilidad )
	{
		$this->margen_utilidad = $margen_utilidad;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es Descuento que se apicar a esta clasificaciond e producto
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es Descuento que se apicar a esta clasificaciond e producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

}