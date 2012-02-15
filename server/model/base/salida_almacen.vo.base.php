<?php
/** Value Object file for table salida_almacen.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class SalidaAlmacen extends VO
{
	/**
	  * Constructor de SalidaAlmacen
	  * 
	  * Para construir un objeto de tipo SalidaAlmacen debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return SalidaAlmacen
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_salida_almacen']) ){
				$this->id_salida_almacen = $data['id_salida_almacen'];
			}
			if( isset($data['id_almacen']) ){
				$this->id_almacen = $data['id_almacen'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['fecha_registro']) ){
				$this->fecha_registro = $data['fecha_registro'];
			}
			if( isset($data['motivo']) ){
				$this->motivo = $data['motivo'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto SalidaAlmacen en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_salida_almacen" => $this->id_salida_almacen,
			"id_almacen" => $this->id_almacen,
			"id_usuario" => $this->id_usuario,
			"fecha_registro" => $this->fecha_registro,
			"motivo" => $this->motivo
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_salida_almacen
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_salida_almacen;

	/**
	  * id_almacen
	  * 
	  * Id del almacen del cual sale producto<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_almacen;

	/**
	  * id_usuario
	  * 
	  * Id del usuario que registra<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_usuario;

	/**
	  * fecha_registro
	  * 
	  * Fecha en que se registra el movimiento<br>
	  * @access public
	  * @var datetime
	  */
	public $fecha_registro;

	/**
	  * motivo
	  * 
	  * motivo por le cual sale producto del almacen<br>
	  * @access public
	  * @var varchar(255)
	  */
	public $motivo;

	/**
	  * getIdSalidaAlmacen
	  * 
	  * Get the <i>id_salida_almacen</i> property for this object. Donde <i>id_salida_almacen</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdSalidaAlmacen()
	{
		return $this->id_salida_almacen;
	}

	/**
	  * setIdSalidaAlmacen( $id_salida_almacen )
	  * 
	  * Set the <i>id_salida_almacen</i> property for this object. Donde <i>id_salida_almacen</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_salida_almacen</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdSalidaAlmacen( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdSalidaAlmacen( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdSalidaAlmacen( $id_salida_almacen )
	{
		$this->id_salida_almacen = $id_salida_almacen;
	}

	/**
	  * getIdAlmacen
	  * 
	  * Get the <i>id_almacen</i> property for this object. Donde <i>id_almacen</i> es Id del almacen del cual sale producto
	  * @return int(11)
	  */
	final public function getIdAlmacen()
	{
		return $this->id_almacen;
	}

	/**
	  * setIdAlmacen( $id_almacen )
	  * 
	  * Set the <i>id_almacen</i> property for this object. Donde <i>id_almacen</i> es Id del almacen del cual sale producto.
	  * Una validacion basica se hara aqui para comprobar que <i>id_almacen</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdAlmacen( $id_almacen )
	{
		$this->id_almacen = $id_almacen;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que registra
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que registra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getFechaRegistro
	  * 
	  * Get the <i>fecha_registro</i> property for this object. Donde <i>fecha_registro</i> es Fecha en que se registra el movimiento
	  * @return datetime
	  */
	final public function getFechaRegistro()
	{
		return $this->fecha_registro;
	}

	/**
	  * setFechaRegistro( $fecha_registro )
	  * 
	  * Set the <i>fecha_registro</i> property for this object. Donde <i>fecha_registro</i> es Fecha en que se registra el movimiento.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_registro</i> es de tipo <i>datetime</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param datetime
	  */
	final public function setFechaRegistro( $fecha_registro )
	{
		$this->fecha_registro = $fecha_registro;
	}

	/**
	  * getMotivo
	  * 
	  * Get the <i>motivo</i> property for this object. Donde <i>motivo</i> es motivo por le cual sale producto del almacen
	  * @return varchar(255)
	  */
	final public function getMotivo()
	{
		return $this->motivo;
	}

	/**
	  * setMotivo( $motivo )
	  * 
	  * Set the <i>motivo</i> property for this object. Donde <i>motivo</i> es motivo por le cual sale producto del almacen.
	  * Una validacion basica se hara aqui para comprobar que <i>motivo</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setMotivo( $motivo )
	{
		$this->motivo = $motivo;
	}

}
