<?php
/** Value Object file for table compra_cliente.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class CompraCliente extends VO
{
	/**
	  * Constructor de CompraCliente
	  * 
	  * Para construir un objeto de tipo CompraCliente debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CompraCliente
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra']) ){
				$this->id_compra = $data['id_compra'];
			}
			if( isset($data['id_cliente']) ){
				$this->id_cliente = $data['id_cliente'];
			}
			if( isset($data['tipo_compra']) ){
				$this->tipo_compra = $data['tipo_compra'];
			}
			if( isset($data['tipo_pago']) ){
				$this->tipo_pago = $data['tipo_pago'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['subtotal']) ){
				$this->subtotal = $data['subtotal'];
			}
			if( isset($data['iva']) ){
				$this->iva = $data['iva'];
			}
			if( isset($data['descuento']) ){
				$this->descuento = $data['descuento'];
			}
			if( isset($data['total']) ){
				$this->total = $data['total'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['pagado']) ){
				$this->pagado = $data['pagado'];
			}
			if( isset($data['cancelada']) ){
				$this->cancelada = $data['cancelada'];
			}
			if( isset($data['ip']) ){
				$this->ip = $data['ip'];
			}
			if( isset($data['liquidada']) ){
				$this->liquidada = $data['liquidada'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CompraCliente en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra" => $this->id_compra,
			"id_cliente" => $this->id_cliente,
			"tipo_compra" => $this->tipo_compra,
			"tipo_pago" => $this->tipo_pago,
			"fecha" => $this->fecha,
			"subtotal" => $this->subtotal,
			"iva" => $this->iva,
			"descuento" => $this->descuento,
			"total" => $this->total,
			"id_sucursal" => $this->id_sucursal,
			"id_usuario" => $this->id_usuario,
			"pagado" => $this->pagado,
			"cancelada" => $this->cancelada,
			"ip" => $this->ip,
			"liquidada" => $this->liquidada
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_compra
	  * 
	  * id de compra<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra;

	/**
	  * id_cliente
	  * 
	  * cliente al que se le compro<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cliente;

	/**
	  * tipo_compra
	  * 
	  * tipo de compra, contado o credito<br>
	  * @access protected
	  * @var enum('credito','contado')
	  */
	protected $tipo_compra;

	/**
	  * tipo_pago
	  * 
	  * tipo de pago para esta compra en caso de ser a contado<br>
	  * @access protected
	  * @var enum('efectivo','cheque','tarjeta')
	  */
	protected $tipo_pago;

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
	  * subtotal de la compra, puede ser nulo<br>
	  * @access protected
	  * @var float
	  */
	protected $subtotal;

	/**
	  * iva
	  * 
	  * iva agregado por la compra, depende de cada sucursal<br>
	  * @access protected
	  * @var float
	  */
	protected $iva;

	/**
	  * descuento
	  * 
	  * descuento aplicado a esta compra<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * total
	  * 
	  * total de esta compra<br>
	  * @access protected
	  * @var float
	  */
	protected $total;

	/**
	  * id_sucursal
	  * 
	  * sucursal de la compra<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * 
	  * empleado que lo vendio<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * pagado
	  * 
	  * porcentaje pagado de esta compra<br>
	  * @access protected
	  * @var float
	  */
	protected $pagado;

	/**
	  * cancelada
	  * 
	  * verdadero si esta compra ha sido cancelada, falso si no<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $cancelada;

	/**
	  * ip
	  * 
	  * ip de donde provino esta compra<br>
	  * @access protected
	  * @var varchar(16)
	  */
	protected $ip;

	/**
	  * liquidada
	  * 
	  * Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $liquidada;

	/**
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es id de compra.
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
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le compro.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
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
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	final public function getTipoPago()
	{
		return $this->tipo_pago;
	}

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para esta compra en caso de ser a contado.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	final public function setTipoPago( $tipo_pago )
	{
		$this->tipo_pago = $tipo_pago;
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
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la compra, puede ser nulo.
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
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la compra, depende de cada sucursal
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la compra, depende de cada sucursal.
	  * Una validacion basica se hara aqui para comprobar que <i>iva</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
	}

	/**
	  * getDescuento
	  * 
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>descuento</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setDescuento( $descuento )
	{
		$this->descuento = $descuento;
	}

	/**
	  * getTotal
	  * 
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra
	  * @return float
	  */
	final public function getTotal()
	{
		return $this->total;
	}

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>total</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotal( $total )
	{
		$this->total = $total;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la compra.
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
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es empleado que lo vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getPagado
	  * 
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra
	  * @return float
	  */
	final public function getPagado()
	{
		return $this->pagado;
	}

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPagado( $pagado )
	{
		$this->pagado = $pagado;
	}

	/**
	  * getCancelada
	  * 
	  * Get the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no
	  * @return tinyint(1)
	  */
	final public function getCancelada()
	{
		return $this->cancelada;
	}

	/**
	  * setCancelada( $cancelada )
	  * 
	  * Set the <i>cancelada</i> property for this object. Donde <i>cancelada</i> es verdadero si esta compra ha sido cancelada, falso si no.
	  * Una validacion basica se hara aqui para comprobar que <i>cancelada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setCancelada( $cancelada )
	{
		$this->cancelada = $cancelada;
	}

	/**
	  * getIp
	  * 
	  * Get the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra
	  * @return varchar(16)
	  */
	final public function getIp()
	{
		return $this->ip;
	}

	/**
	  * setIp( $ip )
	  * 
	  * Set the <i>ip</i> property for this object. Donde <i>ip</i> es ip de donde provino esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>ip</i> es de tipo <i>varchar(16)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(16)
	  */
	final public function setIp( $ip )
	{
		$this->ip = $ip;
	}

	/**
	  * getLiquidada
	  * 
	  * Get the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente
	  * @return tinyint(1)
	  */
	final public function getLiquidada()
	{
		return $this->liquidada;
	}

	/**
	  * setLiquidada( $liquidada )
	  * 
	  * Set the <i>liquidada</i> property for this object. Donde <i>liquidada</i> es Verdadero si esta compra ha sido liquidada, falso si hay un saldo pendiente.
	  * Una validacion basica se hara aqui para comprobar que <i>liquidada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setLiquidada( $liquidada )
	{
		$this->liquidada = $liquidada;
	}

}
