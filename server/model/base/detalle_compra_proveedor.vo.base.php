<?php
/** Value Object file for table detalle_compra_proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez
  * @access public
  * @package docs
  * 
  */

class DetalleCompraProveedor extends VO
{
	/**
	  * Constructor de DetalleCompraProveedor
	  * 
	  * Para construir un objeto de tipo DetalleCompraProveedor debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return DetalleCompraProveedor
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra_proveedor']) ){
				$this->id_compra_proveedor = $data['id_compra_proveedor'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['variedad']) ){
				$this->variedad = $data['variedad'];
			}
			if( isset($data['arpillas']) ){
				$this->arpillas = $data['arpillas'];
			}
			if( isset($data['kg']) ){
				$this->kg = $data['kg'];
			}
			if( isset($data['precio_por_kg']) ){
				$this->precio_por_kg = $data['precio_por_kg'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto DetalleCompraProveedor en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra_proveedor" => $this->id_compra_proveedor,
			"id_producto" => $this->id_producto,
			"variedad" => $this->variedad,
			"arpillas" => $this->arpillas,
			"kg" => $this->kg,
			"precio_por_kg" => $this->precio_por_kg
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_compra_proveedor
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra_proveedor;

	/**
	  * id_producto
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * variedad
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $variedad;

	/**
	  * arpillas
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $arpillas;

	/**
	  * kg
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $kg;

	/**
	  * precio_por_kg
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $precio_por_kg;

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdCompraProveedor()
	{
		return $this->id_compra_proveedor;
	}

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCompraProveedor( $id_compra_proveedor )
	{
		$this->id_compra_proveedor = $id_compra_proveedor;
	}

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
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getVariedad
	  * 
	  * Get the <i>variedad</i> property for this object. Donde <i>variedad</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getVariedad()
	{
		return $this->variedad;
	}

	/**
	  * setVariedad( $variedad )
	  * 
	  * Set the <i>variedad</i> property for this object. Donde <i>variedad</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>variedad</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setVariedad( $variedad )
	{
		$this->variedad = $variedad;
	}

	/**
	  * getArpillas
	  * 
	  * Get the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getArpillas()
	{
		return $this->arpillas;
	}

	/**
	  * setArpillas( $arpillas )
	  * 
	  * Set the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>arpillas</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setArpillas( $arpillas )
	{
		$this->arpillas = $arpillas;
	}

	/**
	  * getKg
	  * 
	  * Get the <i>kg</i> property for this object. Donde <i>kg</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getKg()
	{
		return $this->kg;
	}

	/**
	  * setKg( $kg )
	  * 
	  * Set the <i>kg</i> property for this object. Donde <i>kg</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>kg</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setKg( $kg )
	{
		$this->kg = $kg;
	}

	/**
	  * getPrecioPorKg
	  * 
	  * Get the <i>precio_por_kg</i> property for this object. Donde <i>precio_por_kg</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getPrecioPorKg()
	{
		return $this->precio_por_kg;
	}

	/**
	  * setPrecioPorKg( $precio_por_kg )
	  * 
	  * Set the <i>precio_por_kg</i> property for this object. Donde <i>precio_por_kg</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_por_kg</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setPrecioPorKg( $precio_por_kg )
	{
		$this->precio_por_kg = $precio_por_kg;
	}

}
