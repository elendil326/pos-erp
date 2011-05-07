<?php
/** Value Object file for table inventario_maestro.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class InventarioMaestro extends VO
{
	/**
	  * Constructor de InventarioMaestro
	  * 
	  * Para construir un objeto de tipo InventarioMaestro debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return InventarioMaestro
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['id_compra_proveedor']) ){
				$this->id_compra_proveedor = $data['id_compra_proveedor'];
			}
			if( isset($data['existencias']) ){
				$this->existencias = $data['existencias'];
			}
			if( isset($data['existencias_procesadas']) ){
				$this->existencias_procesadas = $data['existencias_procesadas'];
			}
			if( isset($data['sitio_descarga']) ){
				$this->sitio_descarga = $data['sitio_descarga'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto InventarioMaestro en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_producto" => $this->id_producto,
			"id_compra_proveedor" => $this->id_compra_proveedor,
			"existencias" => $this->existencias,
			"existencias_procesadas" => $this->existencias_procesadas,
			"sitio_descarga" => $this->sitio_descarga
		); 
	return json_encode($vec); 
	}
	
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
	  * id_compra_proveedor
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra_proveedor;

	/**
	  * existencias
	  * 
	  *  [Campo no documentado]<br>
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
	  * sitio_descarga
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $sitio_descarga;

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
	  * getExistencias
	  * 
	  * Get the <i>existencias</i> property for this object. Donde <i>existencias</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getExistencias()
	{
		return $this->existencias;
	}

	/**
	  * setExistencias( $existencias )
	  * 
	  * Set the <i>existencias</i> property for this object. Donde <i>existencias</i> es  [Campo no documentado].
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

	/**
	  * getSitioDescarga
	  * 
	  * Get the <i>sitio_descarga</i> property for this object. Donde <i>sitio_descarga</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getSitioDescarga()
	{
		return $this->sitio_descarga;
	}

	/**
	  * setSitioDescarga( $sitio_descarga )
	  * 
	  * Set the <i>sitio_descarga</i> property for this object. Donde <i>sitio_descarga</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sitio_descarga</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setSitioDescarga( $sitio_descarga )
	{
		$this->sitio_descarga = $sitio_descarga;
	}

}
