<?php
/** Value Object file for table inspeccion_consignacion_producto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class InspeccionConsignacionProducto extends VO
{
	/**
	  * Constructor de InspeccionConsignacionProducto
	  * 
	  * Para construir un objeto de tipo InspeccionConsignacionProducto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return InspeccionConsignacionProducto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_inspeccion_consignacion']) ){
				$this->id_inspeccion_consignacion = $data['id_inspeccion_consignacion'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
			if( isset($data['actual']) ){
				$this->actual = $data['actual'];
			}
			if( isset($data['solicitado']) ){
				$this->solicitado = $data['solicitado'];
			}
			if( isset($data['devuelto']) ){
				$this->devuelto = $data['devuelto'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto InspeccionConsignacionProducto en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_inspeccion_consignacion" => $this->id_inspeccion_consignacion,
			"id_producto" => $this->id_producto,
			"cantidad" => $this->cantidad,
			"actual" => $this->actual,
			"solicitado" => $this->solicitado,
			"devuelto" => $this->devuelto
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_inspeccion_consignacion
	  * 
	  * Id de la isnpeccion de consignacion<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_inspeccion_consignacion;

	/**
	  * id_producto
	  * 
	  * id del producto<br>
	  * <b>Llave Primaria</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_producto;

	/**
	  * cantidad
	  * 
	  * cantidad del producto<br>
	  * @access public
	  * @var float
	  */
	public $cantidad;

	/**
	  * actual
	  * 
	  * true si la cantidad se refiere a la cantidad actual de ese producto<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $actual;

	/**
	  * solicitado
	  * 
	  * true si la cantidad se refiere a la cantidad de ese producto que se solicita<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $solicitado;

	/**
	  * devuelto
	  * 
	  * true si la cantidad de ese producto es devuelta<br>
	  * @access public
	  * @var tinyint(1)
	  */
	public $devuelto;

	/**
	  * getIdInspeccionConsignacion
	  * 
	  * Get the <i>id_inspeccion_consignacion</i> property for this object. Donde <i>id_inspeccion_consignacion</i> es Id de la isnpeccion de consignacion
	  * @return int(11)
	  */
	final public function getIdInspeccionConsignacion()
	{
		return $this->id_inspeccion_consignacion;
	}

	/**
	  * setIdInspeccionConsignacion( $id_inspeccion_consignacion )
	  * 
	  * Set the <i>id_inspeccion_consignacion</i> property for this object. Donde <i>id_inspeccion_consignacion</i> es Id de la isnpeccion de consignacion.
	  * Una validacion basica se hara aqui para comprobar que <i>id_inspeccion_consignacion</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdInspeccionConsignacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdInspeccionConsignacion( $id_inspeccion_consignacion )
	{
		$this->id_inspeccion_consignacion = $id_inspeccion_consignacion;
	}

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
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdProducto( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getCantidad
	  * 
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad del producto
	  * @return float
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad del producto.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getActual
	  * 
	  * Get the <i>actual</i> property for this object. Donde <i>actual</i> es true si la cantidad se refiere a la cantidad actual de ese producto
	  * @return tinyint(1)
	  */
	final public function getActual()
	{
		return $this->actual;
	}

	/**
	  * setActual( $actual )
	  * 
	  * Set the <i>actual</i> property for this object. Donde <i>actual</i> es true si la cantidad se refiere a la cantidad actual de ese producto.
	  * Una validacion basica se hara aqui para comprobar que <i>actual</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActual( $actual )
	{
		$this->actual = $actual;
	}

	/**
	  * getSolicitado
	  * 
	  * Get the <i>solicitado</i> property for this object. Donde <i>solicitado</i> es true si la cantidad se refiere a la cantidad de ese producto que se solicita
	  * @return tinyint(1)
	  */
	final public function getSolicitado()
	{
		return $this->solicitado;
	}

	/**
	  * setSolicitado( $solicitado )
	  * 
	  * Set the <i>solicitado</i> property for this object. Donde <i>solicitado</i> es true si la cantidad se refiere a la cantidad de ese producto que se solicita.
	  * Una validacion basica se hara aqui para comprobar que <i>solicitado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setSolicitado( $solicitado )
	{
		$this->solicitado = $solicitado;
	}

	/**
	  * getDevuelto
	  * 
	  * Get the <i>devuelto</i> property for this object. Donde <i>devuelto</i> es true si la cantidad de ese producto es devuelta
	  * @return tinyint(1)
	  */
	final public function getDevuelto()
	{
		return $this->devuelto;
	}

	/**
	  * setDevuelto( $devuelto )
	  * 
	  * Set the <i>devuelto</i> property for this object. Donde <i>devuelto</i> es true si la cantidad de ese producto es devuelta.
	  * Una validacion basica se hara aqui para comprobar que <i>devuelto</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setDevuelto( $devuelto )
	{
		$this->devuelto = $devuelto;
	}

}
