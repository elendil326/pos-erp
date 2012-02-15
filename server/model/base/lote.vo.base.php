<?php
/** Value Object file for table lote.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Anonymous
  * @access public
  * @package docs
  * 
  */

class Lote extends VO
{
	/**
	  * Constructor de Lote
	  * 
	  * Para construir un objeto de tipo Lote debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Lote
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_lote']) ){
				$this->id_lote = $data['id_lote'];
			}
			if( isset($data['id_compra']) ){
				$this->id_compra = $data['id_compra'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['fecha_ingreso']) ){
				$this->fecha_ingreso = $data['fecha_ingreso'];
			}
			if( isset($data['observaciones']) ){
				$this->observaciones = $data['observaciones'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Lote en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_lote" => $this->id_lote,
			"id_compra" => $this->id_compra,
			"id_usuario" => $this->id_usuario,
			"fecha_ingreso" => $this->fecha_ingreso,
			"observaciones" => $this->observaciones
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_lote
	  * 
	  * Id del lote<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access public
	  * @var int(11)
	  */
	public $id_lote;

	/**
	  * id_compra
	  * 
	  * Id de la compra<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_compra;

	/**
	  * id_usuario
	  * 
	  * Id del usuario que lo recibio<br>
	  * @access public
	  * @var int(11)
	  */
	public $id_usuario;

	/**
	  * fecha_ingreso
	  * 
	  * Fecha en la que el lote ingresa al almacen<br>
	  * @access public
	  * @var datetime
	  */
	public $fecha_ingreso;

	/**
	  * observaciones
	  * 
	  *  [Campo no documentado]<br>
	  * @access public
	  * @var int(11)
	  */
	public $observaciones;

	/**
	  * getIdLote
	  * 
	  * Get the <i>id_lote</i> property for this object. Donde <i>id_lote</i> es Id del lote
	  * @return int(11)
	  */
	final public function getIdLote()
	{
		return $this->id_lote;
	}

	/**
	  * setIdLote( $id_lote )
	  * 
	  * Set the <i>id_lote</i> property for this object. Donde <i>id_lote</i> es Id del lote.
	  * Una validacion basica se hara aqui para comprobar que <i>id_lote</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdLote( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdLote( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdLote( $id_lote )
	{
		$this->id_lote = $id_lote;
	}

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es Id de la compra
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es Id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que lo recibio
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que lo recibio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getFechaIngreso
	  * 
	  * Get the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha en la que el lote ingresa al almacen
	  * @return datetime
	  */
	final public function getFechaIngreso()
	{
		return $this->fecha_ingreso;
	}

	/**
	  * setFechaIngreso( $fecha_ingreso )
	  * 
	  * Set the <i>fecha_ingreso</i> property for this object. Donde <i>fecha_ingreso</i> es Fecha en la que el lote ingresa al almacen.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_ingreso</i> es de tipo <i>datetime</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param datetime
	  */
	final public function setFechaIngreso( $fecha_ingreso )
	{
		$this->fecha_ingreso = $fecha_ingreso;
	}

	/**
	  * getObservaciones
	  * 
	  * Get the <i>observaciones</i> property for this object. Donde <i>observaciones</i> es  [Campo no documentado]
	  * @return int(11)
	  */
	final public function getObservaciones()
	{
		return $this->observaciones;
	}

	/**
	  * setObservaciones( $observaciones )
	  * 
	  * Set the <i>observaciones</i> property for this object. Donde <i>observaciones</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>observaciones</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setObservaciones( $observaciones )
	{
		$this->observaciones = $observaciones;
	}

}
