<?php
/** Value Object file for table compra_sucursal.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class CompraSucursal extends VO
{
	/**
	  * Constructor de CompraSucursal
	  * 
	  * Para construir un objeto de tipo CompraSucursal debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CompraSucursal
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra']) ){
				$this->id_compra = $data['id_compra'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['subtotal']) ){
				$this->subtotal = $data['subtotal'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['id_proveedor']) ){
				$this->id_proveedor = $data['id_proveedor'];
			}
			if( isset($data['pagado']) ){
				$this->pagado = $data['pagado'];
			}
			if( isset($data['liquidado']) ){
				$this->liquidado = $data['liquidado'];
			}
			if( isset($data['total']) ){
				$this->total = $data['total'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CompraSucursal en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra" => $this->id_compra,
			"fecha" => $this->fecha,
			"subtotal" => $this->subtotal,
			"id_sucursal" => $this->id_sucursal,
			"id_usuario" => $this->id_usuario,
			"id_proveedor" => $this->id_proveedor,
			"pagado" => $this->pagado,
			"liquidado" => $this->liquidado,
			"total" => $this->total
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_compra
	  * 
	  * id de la compra<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra;

	/**
	  * fecha
	  * 
	  * fecha de compra<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * subtotal
	  * 
	  * subtotal de compra<br>
	  * @access protected
	  * @var float
	  */
	protected $subtotal;

	/**
	  * id_sucursal
	  * 
	  * sucursal en que se compro<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * 
	  * quien realizo la compra<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * id_proveedor
	  * 
	  * En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null.<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_proveedor;

	/**
	  * pagado
	  * 
	  * total de pago abonado a esta compra<br>
	  * @access protected
	  * @var float
	  */
	protected $pagado;

	/**
	  * liquidado
	  * 
	  * indica si la cuenta ha sido liquidada o no<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $liquidado;

	/**
	  * total
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $total;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompra( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getSubtotal
	  * 
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de compra
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de compra.
	  * Una validacion basica se hara aqui para comprobar que <i>subtotal</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setSubtotal( $subtotal )
	{
		$this->subtotal = $subtotal;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en que se compro
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal en que se compro.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es quien realizo la compra
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es quien realizo la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null.
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es En caso de ser una compra a un proveedor externo, contendra el id de ese proveedor, en caso de ser una compra a centro de distribucion este campo sera null..
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es total de pago abonado a esta compra
	  * @return float
	  */
	final public function getPagado()
	{
		return $this->pagado;
	}

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es total de pago abonado a esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPagado( $pagado )
	{
		$this->pagado = $pagado;
	}

	/**
	  * getLiquidado
	  * 
	  * Get the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es indica si la cuenta ha sido liquidada o no
	  * @return tinyint(1)
	  */
	final public function getLiquidado()
	{
		return $this->liquidado;
	}

	/**
	  * setLiquidado( $liquidado )
	  * 
	  * Set the <i>liquidado</i> property for this object. Donde <i>liquidado</i> es indica si la cuenta ha sido liquidada o no.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidado</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setLiquidado( $liquidado )
	{
		$this->liquidado = $liquidado;
	}

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getTotal()
	{
		return $this->total;
	}

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotal( $total )
	{
		$this->total = $total;
	}

}
