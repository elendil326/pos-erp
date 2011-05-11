<?php
/** Value Object file for table inventario.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
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
			if( isset($data['escala']) ){
				$this->escala = $data['escala'];
			}
			if( isset($data['tratamiento']) ){
				$this->tratamiento = $data['tratamiento'];
			}
			if( isset($data['agrupacion']) ){
				$this->agrupacion = $data['agrupacion'];
			}
			if( isset($data['agrupacionTam']) ){
				$this->agrupacionTam = $data['agrupacionTam'];
			}
			if( isset($data['activo']) ){
				$this->activo = $data['activo'];
			}
			if( isset($data['precio_por_agrupacion']) ){
				$this->precio_por_agrupacion = $data['precio_por_agrupacion'];
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
		$vec = array( 
			"id_producto" => $this->id_producto,
			"descripcion" => $this->descripcion,
			"escala" => $this->escala,
			"tratamiento" => $this->tratamiento,
			"agrupacion" => $this->agrupacion,
			"agrupacionTam" => $this->agrupacionTam,
			"activo" => $this->activo,
			"precio_por_agrupacion" => $this->precio_por_agrupacion
		); 
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
	  * escala
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var enum('kilogramo','pieza','litro','unidad')
	  */
	protected $escala;

	/**
	  * tratamiento
	  * 
	  * Tipo de tratatiento si es que existe para este producto.<br>
	  * @access protected
	  * @var enum('limpia')
	  */
	protected $tratamiento;

	/**
	  * agrupacion
	  * 
	  * La agrupacion de este producto<br>
	  * @access protected
	  * @var varchar(8)
	  */
	protected $agrupacion;

	/**
	  * agrupacionTam
	  * 
	  * El tamano de cada agrupacion<br>
	  * @access protected
	  * @var float
	  */
	protected $agrupacionTam;

	/**
	  * activo
	  * 
	  * si este producto esta activo o no en el sistema<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activo;

	/**
	  * precio_por_agrupacion
	  * 
	  * Verdadero cuando el precio marcado es por agrupacion, de ser falso, el precio marcado es por unidad, sea la que la escala dictamine<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $precio_por_agrupacion;

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
	  * getEscala
	  * 
	  * Get the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado]
	  * @return enum('kilogramo','pieza','litro','unidad')
	  */
	final public function getEscala()
	{
		return $this->escala;
	}

	/**
	  * setEscala( $escala )
	  * 
	  * Set the <i>escala</i> property for this object. Donde <i>escala</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>escala</i> es de tipo <i>enum('kilogramo','pieza','litro','unidad')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('kilogramo','pieza','litro','unidad')
	  */
	final public function setEscala( $escala )
	{
		$this->escala = $escala;
	}

	/**
	  * getTratamiento
	  * 
	  * Get the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto.
	  * @return enum('limpia')
	  */
	final public function getTratamiento()
	{
		return $this->tratamiento;
	}

	/**
	  * setTratamiento( $tratamiento )
	  * 
	  * Set the <i>tratamiento</i> property for this object. Donde <i>tratamiento</i> es Tipo de tratatiento si es que existe para este producto..
	  * Una validacion basica se hara aqui para comprobar que <i>tratamiento</i> es de tipo <i>enum('limpia')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('limpia')
	  */
	final public function setTratamiento( $tratamiento )
	{
		$this->tratamiento = $tratamiento;
	}

	/**
	  * getAgrupacion
	  * 
	  * Get the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto
	  * @return varchar(8)
	  */
	final public function getAgrupacion()
	{
		return $this->agrupacion;
	}

	/**
	  * setAgrupacion( $agrupacion )
	  * 
	  * Set the <i>agrupacion</i> property for this object. Donde <i>agrupacion</i> es La agrupacion de este producto.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacion</i> es de tipo <i>varchar(8)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(8)
	  */
	final public function setAgrupacion( $agrupacion )
	{
		$this->agrupacion = $agrupacion;
	}

	/**
	  * getAgrupacionTam
	  * 
	  * Get the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion
	  * @return float
	  */
	final public function getAgrupacionTam()
	{
		return $this->agrupacionTam;
	}

	/**
	  * setAgrupacionTam( $agrupacionTam )
	  * 
	  * Set the <i>agrupacionTam</i> property for this object. Donde <i>agrupacionTam</i> es El tamano de cada agrupacion.
	  * Una validacion basica se hara aqui para comprobar que <i>agrupacionTam</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setAgrupacionTam( $agrupacionTam )
	{
		$this->agrupacionTam = $agrupacionTam;
	}

	/**
	  * getActivo
	  * 
	  * Get the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema
	  * @return tinyint(1)
	  */
	final public function getActivo()
	{
		return $this->activo;
	}

	/**
	  * setActivo( $activo )
	  * 
	  * Set the <i>activo</i> property for this object. Donde <i>activo</i> es si este producto esta activo o no en el sistema.
	  * Una validacion basica se hara aqui para comprobar que <i>activo</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActivo( $activo )
	{
		$this->activo = $activo;
	}

	/**
	  * getPrecioPorAgrupacion
	  * 
	  * Get the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es Verdadero cuando el precio marcado es por agrupacion, de ser falso, el precio marcado es por unidad, sea la que la escala dictamine
	  * @return tinyint(1)
	  */
	final public function getPrecioPorAgrupacion()
	{
		return $this->precio_por_agrupacion;
	}

	/**
	  * setPrecioPorAgrupacion( $precio_por_agrupacion )
	  * 
	  * Set the <i>precio_por_agrupacion</i> property for this object. Donde <i>precio_por_agrupacion</i> es Verdadero cuando el precio marcado es por agrupacion, de ser falso, el precio marcado es por unidad, sea la que la escala dictamine.
	  * Una validacion basica se hara aqui para comprobar que <i>precio_por_agrupacion</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setPrecioPorAgrupacion( $precio_por_agrupacion )
	{
		$this->precio_por_agrupacion = $precio_por_agrupacion;
	}

}
