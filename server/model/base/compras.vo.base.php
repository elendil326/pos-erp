<?php
/** Value Object file for table compras.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Compras extends VO
{
	/**
	  * Constructor de Compras
	  * 
	  * Para construir un objeto de tipo Compras debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Compras
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_compra = $data['id_compra'];
			$this->id_proveedor = $data['id_proveedor'];
			$this->tipo_compra = $data['tipo_compra'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Compras en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"id_compra" => $this->id_compra,
		"id_proveedor" => $this->id_proveedor,
		"tipo_compra" => $this->tipo_compra,
		"fecha" => $this->fecha,
		"subtotal" => $this->subtotal,
		"iva" => $this->iva,
		"id_sucursal" => $this->id_sucursal,
		"id_usuario" => $this->id_usuario
		)); 
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
	  * id_proveedor
	  * 
	  * PROVEEDOR AL QUE SE LE COMPRO<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_proveedor;

	/**
	  * tipo_compra
	  * 
	  * tipo de compra, contado o credito<br>
	  * @access protected
	  * @var enum('credito','contado')
	  */
	protected $tipo_compra;

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
	  * iva
	  * 
	  * iva de la compra<br>
	  * @access protected
	  * @var float
	  */
	protected $iva;

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
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es PROVEEDOR AL QUE SE LE COMPRO
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es PROVEEDOR AL QUE SE LE COMPRO.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * getTipoCompra
	  * 
	  * Get the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito
	  * @return enum('credito','contado')
	  */
	final public function getTipoCompra()
	{
		return $this->tipo_compra;
	}

	/**
	  * setTipoCompra( $tipo_compra )
	  * 
	  * Set the <i>tipo_compra</i> property for this object. Donde <i>tipo_compra</i> es tipo de compra, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_compra</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	final public function setTipoCompra( $tipo_compra )
	{
		$this->tipo_compra = $tipo_compra;
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
	  * getIva
	  * 
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva de la compra
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>iva</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
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

}
