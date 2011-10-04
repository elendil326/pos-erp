<?php
/** Value Object file for table traspaso_producto.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Andres
  * @access public
  * @package docs
  * 
  */

class TraspasoProducto extends VO
{
	/**
	  * Constructor de TraspasoProducto
	  * 
	  * Para construir un objeto de tipo TraspasoProducto debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return TraspasoProducto
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_traspaso']) ){
				$this->id_traspaso = $data['id_traspaso'];
			}
			if( isset($data['id_producto']) ){
				$this->id_producto = $data['id_producto'];
			}
			if( isset($data['cantidad']) ){
				$this->cantidad = $data['cantidad'];
			}
			if( isset($data['enviado']) ){
				$this->enviado = $data['enviado'];
			}
			if( isset($data['recibido']) ){
				$this->recibido = $data['recibido'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto TraspasoProducto en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_traspaso" => $this->id_traspaso,
			"id_producto" => $this->id_producto,
			"cantidad" => $this->cantidad,
			"enviado" => $this->enviado,
			"recibido" => $this->recibido
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_traspaso
	  * 
	  * Id del traspaso<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_traspaso;

	/**
	  * id_producto
	  * 
	  * Id del producto a traspasar<br>
	  * <b>Llave Primaria</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_producto;

	/**
	  * cantidad
	  * 
	  * cantidad de producto a traspasar<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $cantidad;

	/**
	  * enviado
	  * 
	  * Verdadero si este registro corresponde a un envio de producto como traspaso<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $enviado;

	/**
	  * recibido
	  * 
	  * Verdadero si este registro corresponde a un recibo de producto cmo traspaso<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $recibido;

	/**
	  * getIdTraspaso
	  * 
	  * Get the <i>id_traspaso</i> property for this object. Donde <i>id_traspaso</i> es Id del traspaso
	  * @return int(11)
	  */
	final public function getIdTraspaso()
	{
		return $this->id_traspaso;
	}

	/**
	  * setIdTraspaso( $id_traspaso )
	  * 
	  * Set the <i>id_traspaso</i> property for this object. Donde <i>id_traspaso</i> es Id del traspaso.
	  * Una validacion basica se hara aqui para comprobar que <i>id_traspaso</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdTraspaso( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdTraspaso( $id_traspaso )
	{
		$this->id_traspaso = $id_traspaso;
	}

	/**
	  * getIdProducto
	  * 
	  * Get the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es Id del producto a traspasar
	  * @return int(11)
	  */
	final public function getIdProducto()
	{
		return $this->id_producto;
	}

	/**
	  * setIdProducto( $id_producto )
	  * 
	  * Set the <i>id_producto</i> property for this object. Donde <i>id_producto</i> es Id del producto a traspasar.
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
	  * Get the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad de producto a traspasar
	  * @return int(11)
	  */
	final public function getCantidad()
	{
		return $this->cantidad;
	}

	/**
	  * setCantidad( $cantidad )
	  * 
	  * Set the <i>cantidad</i> property for this object. Donde <i>cantidad</i> es cantidad de producto a traspasar.
	  * Una validacion basica se hara aqui para comprobar que <i>cantidad</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setCantidad( $cantidad )
	{
		$this->cantidad = $cantidad;
	}

	/**
	  * getEnviado
	  * 
	  * Get the <i>enviado</i> property for this object. Donde <i>enviado</i> es Verdadero si este registro corresponde a un envio de producto como traspaso
	  * @return tinyint(1)
	  */
	final public function getEnviado()
	{
		return $this->enviado;
	}

	/**
	  * setEnviado( $enviado )
	  * 
	  * Set the <i>enviado</i> property for this object. Donde <i>enviado</i> es Verdadero si este registro corresponde a un envio de producto como traspaso.
	  * Una validacion basica se hara aqui para comprobar que <i>enviado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setEnviado( $enviado )
	{
		$this->enviado = $enviado;
	}

	/**
	  * getRecibido
	  * 
	  * Get the <i>recibido</i> property for this object. Donde <i>recibido</i> es Verdadero si este registro corresponde a un recibo de producto cmo traspaso
	  * @return tinyint(1)
	  */
	final public function getRecibido()
	{
		return $this->recibido;
	}

	/**
	  * setRecibido( $recibido )
	  * 
	  * Set the <i>recibido</i> property for this object. Donde <i>recibido</i> es Verdadero si este registro corresponde a un recibo de producto cmo traspaso.
	  * Una validacion basica se hara aqui para comprobar que <i>recibido</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setRecibido( $recibido )
	{
		$this->recibido = $recibido;
	}

}
