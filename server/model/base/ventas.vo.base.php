<?php
/** Value Object file for table ventas.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class Ventas extends VO
{
	/**
	  * Constructor de Ventas
	  * 
	  * Para construir un objeto de tipo Ventas debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Ventas
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_venta = $data['id_venta'];
			$this->id_cliente = $data['id_cliente'];
			$this->tipo_venta = $data['tipo_venta'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->descuento = $data['descuento'];
			$this->total = $data['total'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
			$this->pagado = $data['pagado'];
			$this->ip = $data['ip'];
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Ventas en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	  public function __toString( )
	  { 
		$vec = array( 
		"id_venta" => $this->id_venta,
		"id_cliente" => $this->id_cliente,
		"tipo_venta" => $this->tipo_venta,
		"fecha" => $this->fecha,
		"subtotal" => $this->subtotal,
		"iva" => $this->iva,
		"descuento" => $this->descuento,
		"total" => $this->total,
		"id_sucursal" => $this->id_sucursal,
		"id_usuario" => $this->id_usuario,
		"pagado" => $this->pagado,
		"ip" => $this->ip
		); 
	return json_encode($vec); 
	}
	/**
	  * id_venta
	  * 
	  * id de venta<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_venta;

	/**
	  * id_cliente
	  * 
	  * cliente al que se le vendio<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_cliente;

	/**
	  * tipo_venta
	  * 
	  * tipo de venta, contado o credito<br>
	  * @access protected
	  * @var enum('credito','contado')
	  */
	protected $tipo_venta;

	/**
	  * fecha
	  * 
	  * fecha de venta<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * subtotal
	  * 
	  * subtotal de la venta, puede ser nulo<br>
	  * @access protected
	  * @var float
	  */
	protected $subtotal;

	/**
	  * iva
	  * 
	  * iva agregado por la venta, depende de cada sucursal<br>
	  * @access protected
	  * @var float
	  */
	protected $iva;

	/**
	  * descuento
	  * 
	  * descuento aplicado a esta venta<br>
	  * @access protected
	  * @var float
	  */
	protected $descuento;

	/**
	  * total
	  * 
	  * total de esta venta<br>
	  * @access protected
	  * @var float
	  */
	protected $total;

	/**
	  * id_sucursal
	  * 
	  * sucursal de la venta<br>
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
	  * porcentaje pagado de esta venta<br>
	  * @access protected
	  * @var float
	  */
	protected $pagado;

	/**
	  * ip
	  * 
	  * ip de donde provino esta compra<br>
	  * @access protected
	  * @var varchar(16)
	  */
	protected $ip;

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de venta.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdVenta( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * getIdCliente
	  * 
	  * Get the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * setIdCliente( $id_cliente )
	  * 
	  * Set the <i>id_cliente</i> property for this object. Donde <i>id_cliente</i> es cliente al que se le vendio.
	  * Una validacion basica se hara aqui para comprobar que <i>id_cliente</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * getTipoVenta
	  * 
	  * Get the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito
	  * @return enum('credito','contado')
	  */
	final public function getTipoVenta()
	{
		return $this->tipo_venta;
	}

	/**
	  * setTipoVenta( $tipo_venta )
	  * 
	  * Set the <i>tipo_venta</i> property for this object. Donde <i>tipo_venta</i> es tipo de venta, contado o credito.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_venta</i> es de tipo <i>enum('credito','contado')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('credito','contado')
	  */
	final public function setTipoVenta( $tipo_venta )
	{
		$this->tipo_venta = $tipo_venta;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de venta.
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
	  * Get the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * setSubtotal( $subtotal )
	  * 
	  * Set the <i>subtotal</i> property for this object. Donde <i>subtotal</i> es subtotal de la venta, puede ser nulo.
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
	  * Get the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * setIva( $iva )
	  * 
	  * Set the <i>iva</i> property for this object. Donde <i>iva</i> es iva agregado por la venta, depende de cada sucursal.
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
	  * Get the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta
	  * @return float
	  */
	final public function getDescuento()
	{
		return $this->descuento;
	}

	/**
	  * setDescuento( $descuento )
	  * 
	  * Set the <i>descuento</i> property for this object. Donde <i>descuento</i> es descuento aplicado a esta venta.
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
	  * Get the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta
	  * @return float
	  */
	final public function getTotal()
	{
		return $this->total;
	}

	/**
	  * setTotal( $total )
	  * 
	  * Set the <i>total</i> property for this object. Donde <i>total</i> es total de esta venta.
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
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal de la venta.
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
	  * Get the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta
	  * @return float
	  */
	final public function getPagado()
	{
		return $this->pagado;
	}

	/**
	  * setPagado( $pagado )
	  * 
	  * Set the <i>pagado</i> property for this object. Donde <i>pagado</i> es porcentaje pagado de esta venta.
	  * Una validacion basica se hara aqui para comprobar que <i>pagado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPagado( $pagado )
	{
		$this->pagado = $pagado;
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

}
