<?php
/** Value Object file for table pagos_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class PagosVenta extends VO
{
	/**
	  * Constructor de PagosVenta
	  * 
	  * Para construir un objeto de tipo PagosVenta debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PagosVenta
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_pago']) ){
				$this->id_pago = $data['id_pago'];
			}
			if( isset($data['id_venta']) ){
				$this->id_venta = $data['id_venta'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['monto']) ){
				$this->monto = $data['monto'];
			}
			if( isset($data['tipo_pago']) ){
				$this->tipo_pago = $data['tipo_pago'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto PagosVenta en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_pago" => $this->id_pago,
			"id_venta" => $this->id_venta,
			"id_sucursal" => $this->id_sucursal,
			"id_usuario" => $this->id_usuario,
			"fecha" => $this->fecha,
			"monto" => $this->monto,
			"tipo_pago" => $this->tipo_pago
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_pago
	  * 
	  * id de pago del cliente<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_pago;

	/**
	  * id_venta
	  * 
	  * id de la venta a la que se esta pagando<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_venta;

	/**
	  * id_sucursal
	  * 
	  * Donde se realizo el pago<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * 
	  * Quien cobro este pago<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_usuario;

	/**
	  * fecha
	  * 
	  * Fecha en que se registro el pago<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * monto
	  * 
	  * total de credito del cliente<br>
	  * @access protected
	  * @var float
	  */
	protected $monto;

	/**
	  * tipo_pago
	  * 
	  * tipo de pago para este abono<br>
	  * @access protected
	  * @var enum('efectivo','cheque','tarjeta')
	  */
	protected $tipo_pago;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente
	  * @return int(11)
	  */
	final public function getIdPago()
	{
		return $this->id_pago;
	}

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es id de pago del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>id_pago</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdPago( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdPago( $id_pago )
	{
		$this->id_pago = $id_pago;
	}

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es id de la venta a la que se esta pagando.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es Donde se realizo el pago.
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
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Quien cobro este pago.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha en que se registro el pago.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es total de credito del cliente.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

	/**
	  * getTipoPago
	  * 
	  * Get the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono
	  * @return enum('efectivo','cheque','tarjeta')
	  */
	final public function getTipoPago()
	{
		return $this->tipo_pago;
	}

	/**
	  * setTipoPago( $tipo_pago )
	  * 
	  * Set the <i>tipo_pago</i> property for this object. Donde <i>tipo_pago</i> es tipo de pago para este abono.
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_pago</i> es de tipo <i>enum('efectivo','cheque','tarjeta')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('efectivo','cheque','tarjeta')
	  */
	final public function setTipoPago( $tipo_pago )
	{
		$this->tipo_pago = $tipo_pago;
	}

}
