<?php
/** Value Object file for table compra_proveedor_flete.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class CompraProveedorFlete extends VO
{
	/**
	  * Constructor de CompraProveedorFlete
	  * 
	  * Para construir un objeto de tipo CompraProveedorFlete debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CompraProveedorFlete
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra_proveedor']) ){
				$this->id_compra_proveedor = $data['id_compra_proveedor'];
			}
			if( isset($data['chofer']) ){
				$this->chofer = $data['chofer'];
			}
			if( isset($data['marca_camion']) ){
				$this->marca_camion = $data['marca_camion'];
			}
			if( isset($data['placas_camion']) ){
				$this->placas_camion = $data['placas_camion'];
			}
			if( isset($data['modelo_camion']) ){
				$this->modelo_camion = $data['modelo_camion'];
			}
			if( isset($data['costo_flete']) ){
				$this->costo_flete = $data['costo_flete'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CompraProveedorFlete en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra_proveedor" => $this->id_compra_proveedor,
			"chofer" => $this->chofer,
			"marca_camion" => $this->marca_camion,
			"placas_camion" => $this->placas_camion,
			"modelo_camion" => $this->modelo_camion,
			"costo_flete" => $this->costo_flete
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
	  * chofer
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $chofer;

	/**
	  * marca_camion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $marca_camion;

	/**
	  * placas_camion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $placas_camion;

	/**
	  * modelo_camion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $modelo_camion;

	/**
	  * costo_flete
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $costo_flete;

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
	  * getChofer
	  * 
	  * Get the <i>chofer</i> property for this object. Donde <i>chofer</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getChofer()
	{
		return $this->chofer;
	}

	/**
	  * setChofer( $chofer )
	  * 
	  * Set the <i>chofer</i> property for this object. Donde <i>chofer</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>chofer</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setChofer( $chofer )
	{
		$this->chofer = $chofer;
	}

	/**
	  * getMarcaCamion
	  * 
	  * Get the <i>marca_camion</i> property for this object. Donde <i>marca_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getMarcaCamion()
	{
		return $this->marca_camion;
	}

	/**
	  * setMarcaCamion( $marca_camion )
	  * 
	  * Set the <i>marca_camion</i> property for this object. Donde <i>marca_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>marca_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setMarcaCamion( $marca_camion )
	{
		$this->marca_camion = $marca_camion;
	}

	/**
	  * getPlacasCamion
	  * 
	  * Get the <i>placas_camion</i> property for this object. Donde <i>placas_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getPlacasCamion()
	{
		return $this->placas_camion;
	}

	/**
	  * setPlacasCamion( $placas_camion )
	  * 
	  * Set the <i>placas_camion</i> property for this object. Donde <i>placas_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>placas_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setPlacasCamion( $placas_camion )
	{
		$this->placas_camion = $placas_camion;
	}

	/**
	  * getModeloCamion
	  * 
	  * Get the <i>modelo_camion</i> property for this object. Donde <i>modelo_camion</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getModeloCamion()
	{
		return $this->modelo_camion;
	}

	/**
	  * setModeloCamion( $modelo_camion )
	  * 
	  * Set the <i>modelo_camion</i> property for this object. Donde <i>modelo_camion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>modelo_camion</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setModeloCamion( $modelo_camion )
	{
		$this->modelo_camion = $modelo_camion;
	}

	/**
	  * getCostoFlete
	  * 
	  * Get the <i>costo_flete</i> property for this object. Donde <i>costo_flete</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getCostoFlete()
	{
		return $this->costo_flete;
	}

	/**
	  * setCostoFlete( $costo_flete )
	  * 
	  * Set the <i>costo_flete</i> property for this object. Donde <i>costo_flete</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>costo_flete</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCostoFlete( $costo_flete )
	{
		$this->costo_flete = $costo_flete;
	}

}
