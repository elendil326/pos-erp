<?php
/** Value Object file for table actualizacion_de_precio.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class ActualizacionDePrecio extends VO
{
	/**
	  * Constructor de ActualizacionDePrecio
	  * 
	  * Para construir un objeto de tipo ActualizacionDePrecio debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return ActualizacionDePrecio
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_actualizacion']) ){
				$this->id_actualizacion = $data['id_actualizacion'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['precio_venta']) ){
				$this->precio_venta = $data['precio_venta'];
			}
			if( isset($data['precio_venta_procesado']) ){
				$this->precio_venta_procesado = $data['precio_venta_procesado'];
			}
			if( isset($data['precio_intersucursal']) ){
				$this->precio_intersucursal = $data['precio_intersucursal'];
			}
			if( isset($data['precio_intersucursal_procesado']) ){
				$this->precio_intersucursal_procesado = $data['precio_intersucursal_procesado'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto ActualizacionDePrecio en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_actualizacion" => $this->id_actualizacion,
			"id_producto" => $this->id_producto,
			"id_usuario" => $this->id_usuario,
			"precio_venta" => $this->precio_venta,
			"precio_venta_procesado" => $this->precio_venta_procesado,
			"precio_intersucursal" => $this->precio_intersucursal,
			"precio_intersucursal_procesado" => $this->precio_intersucursal_procesado,
			"fecha" => $this->fecha
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_actualizacion
	  * 
	  * id de actualizacion de precio<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(12)
	  */
	protected $id_actualizacion;

	/**
	  * id_producto
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * id_usuario
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * precio_venta
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_venta;

	/**
	  * precio_venta_procesado
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_venta_procesado;

	/**
	  * precio_intersucursal
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_intersucursal;

	/**
	  * precio_intersucursal_procesado
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $precio_intersucursal_procesado;

	/**
	  * fecha
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * getIdActualizacion
	  * 
	  * Get the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio
	  * @return int(12)
	  */
	final public function getIdActualizacion()
	{
		return $this->id_actualizacion;
	}

	/**
	  * setIdActualizacion( $id_actualizacion )
	  * 
	  * Set the <i>id_actualizacion</i> property for this object. Donde <i>id_actualizacion</i> es id de actualizacion de precio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_actualizacion</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdActualizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdActualizacion( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(12)
	  */
	final public function setIdActualizacion( $id_actualizacion )
	{
		$this->id_actualizacion = $id_actualizacion;
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
	  * @param int(11)
	  */
	final public function setIdProducto( $id_producto )
	{
		$this->id_producto = $id_producto;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getPrecioVenta
	  * 
	  * Get the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getPrecioVenta()
	{
		return $this->precio_venta;
	}

	/**
	  * setPrecioVenta( $precio_venta )
	  * 
	  * Set the <i>precio_venta</i> property for this object. Donde <i>precio_venta</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioVenta( $precio_venta )
	{
		$this->precio_venta = $precio_venta;
	}

	/**
	  * getPrecioVentaProcesado
	  * 
	  * Get the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getPrecioVentaProcesado()
	{
		return $this->precio_venta_procesado;
	}

	/**
	  * setPrecioVentaProcesado( $precio_venta_procesado )
	  * 
	  * Set the <i>precio_venta_procesado</i> property for this object. Donde <i>precio_venta_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_venta_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioVentaProcesado( $precio_venta_procesado )
	{
		$this->precio_venta_procesado = $precio_venta_procesado;
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
	  * getPrecioIntersucursalProcesado
	  * 
	  * Get the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getPrecioIntersucursalProcesado()
	{
		return $this->precio_intersucursal_procesado;
	}

	/**
	  * setPrecioIntersucursalProcesado( $precio_intersucursal_procesado )
	  * 
	  * Set the <i>precio_intersucursal_procesado</i> property for this object. Donde <i>precio_intersucursal_procesado</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>precio_intersucursal_procesado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPrecioIntersucursalProcesado( $precio_intersucursal_procesado )
	{
		$this->precio_intersucursal_procesado = $precio_intersucursal_procesado;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

}
