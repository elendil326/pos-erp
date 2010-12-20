<?php
/** Value Object file for table inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Inventario extends VO
{
	/**
	  * Constructor de Inventario
	  * 
	  * Para construir un objeto de tipo Inventario debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Inventario
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['descripcion']) ){
				$this->descripcion = $data['descripcion'];
			}
			if( isset($data['precio_intersucursal']) ){
				$this->precio_intersucursal = $data['precio_intersucursal'];
			}
			if( isset($data['costo']) ){
				$this->costo = $data['costo'];
			}
			if( isset($data['medida']) ){
				$this->medida = $data['medida'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Inventario en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_producto" => $this->id_producto,
		"descripcion" => $this->descripcion,
		"precio_intersucursal" => $this->precio_intersucursal,
		"costo" => $this->costo,
		"medida" => $this->medida
		)); 
	return json_encode($vec); 
	}
	
	/**
	  * id_producto
	  * 
	  * id del producto<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * descripcion
	  * 
	  * descripcion del producto<br>
	  * @access protected
	  * @var varchar(30)
	  */
	protected $descripcion;

	/**
	  * precio_intersucursal
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_intersucursal;

	/**
	  * costo
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $costo;

	/**
	  * medida
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var enum('fraccion','unidad')
	  */
	protected $medida;

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
	  * getDescripcion
	  * 
	  * Get the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto
	  * @return varchar(30)
	  */
	final public function getDescripcion()
	{
		return $this->descripcion;
	}

	/**
	  * setDescripcion( $descripcion )
	  * 
	  * Set the <i>descripcion</i> property for this object. Donde <i>descripcion</i> es descripcion del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>descripcion</i> es de tipo <i>varchar(30)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(30)
	  */
	final public function setDescripcion( $descripcion )
	{
		$this->descripcion = $descripcion;
	}

	/**
	  * getPrecioIntersucursal
	  * 
	  * Get the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getPrecioIntersucursal()
	{
		return $this->precio_intersucursal;
	}

	/**
	  * setPrecioIntersucursal( $precio_intersucursal )
	  * 
	  * Set the <i>precio_intersucursal</i> property for this object. Donde <i>precio_intersucursal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioIntersucursal( $precio_intersucursal )
	{
		$this->precio_intersucursal = $precio_intersucursal;
	}

	/**
	  * getCosto
	  * 
	  * Get the <i>costo</i> property for this object. Donde <i>costo</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getCosto()
	{
		return $this->costo;
	}

	/**
	  * setCosto( $costo )
	  * 
	  * Set the <i>costo</i> property for this object. Donde <i>costo</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>costo</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCosto( $costo )
	{
		$this->costo = $costo;
	}

	/**
	  * getMedida
	  * 
	  * Get the <i>medida</i> property for this object. Donde <i>medida</i> es  [Campo no documentado]
	  * @return enum('fraccion','unidad')
	  */
	final public function getMedida()
	{
		return $this->medida;
	}

	/**
	  * setMedida( $medida )
	  * 
	  * Set the <i>medida</i> property for this object. Donde <i>medida</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>medida</i> es de tipo <i>enum('fraccion','unidad')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('fraccion','unidad')
	  */
	final public function setMedida( $medida )
	{
		$this->medida = $medida;
	}

}
