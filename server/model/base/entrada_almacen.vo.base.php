<?php
/** Value Object file for table entrada_almacen.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class EntradaAlmacen extends VO
{
	/**
	  * Constructor de EntradaAlmacen
	  * 
	  * Para construir un objeto de tipo EntradaAlmacen debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return EntradaAlmacen
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_entrada_almacen']) ){
				$this->id_entrada_almacen = $data['id_entrada_almacen'];
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
	  * Este metodo permite tratar a un objeto EntradaAlmacen en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_entrada_almacen" => $this->id_entrada_almacen,
			"id_almacen" => $this->id_almacen,
			"id_usuario" => $this->id_usuario,
			"fecha_registro" => $this->fecha_registro,
			"motivo" => $this->motivo
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_entrada_almacen
	  * 
	  *  [Campo no documentado]<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_entrada_almacen;

	/**
	  * id_almacen
	  * 
	  * Id del almacen al cual entra producto<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_almacen;

	/**
	  * id_usuario
	  * 
	  * Id del usuario que registra<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * fecha_registro
	  * 
	  * Fecha en que se registra el movimiento<br>
	  * @access protected
	  * @var datetime
	  */
	protected $fecha_registro;

	/**
	  * motivo
	  * 
	  * motivo por le cual entra producto al almacen<br>
	  * @access protected
	  * @var varchar(255)
	  */
	protected $motivo;

	/**
	  * getIdEntradaAlmacen
	  * 
	  * Get the <i>id_entrada_almacen</i> property for this object. Donde <i>id_entrada_almacen</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getIdEntradaAlmacen()
	{
		return $this->id_entrada_almacen;
	}

	/**
	  * setIdEntradaAlmacen( $id_entrada_almacen )
	  * 
	  * Set the <i>id_entrada_almacen</i> property for this object. Donde <i>id_entrada_almacen</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>id_entrada_almacen</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdEntradaAlmacen( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdEntradaAlmacen( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdEntradaAlmacen( $id_entrada_almacen )
	{
		$this->id_entrada_almacen = $id_entrada_almacen;
	}

	/**
	  * getIdAlmacen
	  * 
	  * Get the <i>id_almacen</i> property for this object. Donde <i>id_almacen</i> es Id del almacen al cual entra producto
	  * @return int(11)
	  */
	final public function getIdAlmacen()
	{
		return $this->id_almacen;
	}

	/**
	  * setIdAlmacen( $id_almacen )
	  * 
	  * Set the <i>id_almacen</i> property for this object. Donde <i>id_almacen</i> es Id del almacen al cual entra producto.
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
	  * Get the <i>motivo</i> property for this object. Donde <i>motivo</i> es motivo por le cual entra producto al almacen
	  * @return varchar(255)
	  */
	final public function getMotivo()
	{
		return $this->motivo;
	}

	/**
	  * setMotivo( $motivo )
	  * 
	  * Set the <i>motivo</i> property for this object. Donde <i>motivo</i> es motivo por le cual entra producto al almacen.
	  * Una validacion basica se hara aqui para comprobar que <i>motivo</i> es de tipo <i>varchar(255)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(255)
	  */
	final public function setMotivo( $motivo )
	{
		$this->motivo = $motivo;
	}

}
