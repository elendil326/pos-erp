<?php
/** Value Object file for table corte.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class Corte extends VO
{
	/**
	  * Constructor de Corte
	  * 
	  * Para construir un objeto de tipo Corte debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return Corte
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_corte']) ){
				$this->id_corte = $data['id_corte'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['id_sucursal']) ){
				$this->id_sucursal = $data['id_sucursal'];
			}
			if( isset($data['total_ventas']) ){
				$this->total_ventas = $data['total_ventas'];
			}
			if( isset($data['total_ventas_abonado']) ){
				$this->total_ventas_abonado = $data['total_ventas_abonado'];
			}
			if( isset($data['total_ventas_saldo']) ){
				$this->total_ventas_saldo = $data['total_ventas_saldo'];
			}
			if( isset($data['total_compras']) ){
				$this->total_compras = $data['total_compras'];
			}
			if( isset($data['total_compras_abonado']) ){
				$this->total_compras_abonado = $data['total_compras_abonado'];
			}
			if( isset($data['total_gastos']) ){
				$this->total_gastos = $data['total_gastos'];
			}
			if( isset($data['total_gastos_abonado']) ){
				$this->total_gastos_abonado = $data['total_gastos_abonado'];
			}
			if( isset($data['total_ingresos']) ){
				$this->total_ingresos = $data['total_ingresos'];
			}
			if( isset($data['total_ganancia_neta']) ){
				$this->total_ganancia_neta = $data['total_ganancia_neta'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Corte en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_corte" => $this->id_corte,
			"fecha" => $this->fecha,
			"id_sucursal" => $this->id_sucursal,
			"total_ventas" => $this->total_ventas,
			"total_ventas_abonado" => $this->total_ventas_abonado,
			"total_ventas_saldo" => $this->total_ventas_saldo,
			"total_compras" => $this->total_compras,
			"total_compras_abonado" => $this->total_compras_abonado,
			"total_gastos" => $this->total_gastos,
			"total_gastos_abonado" => $this->total_gastos_abonado,
			"total_ingresos" => $this->total_ingresos,
			"total_ganancia_neta" => $this->total_ganancia_neta
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_corte
	  * 
	  * identificador del corte<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(12)
	  */
	protected $id_corte;

	/**
	  * fecha
	  * 
	  * fecha de este corte<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * id_sucursal
	  * 
	  * sucursal a la que se realizo este corte<br>
	  * @access protected
	  * @var int(12)
	  */
	protected $id_sucursal;

	/**
	  * total_ventas
	  * 
	  * total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas<br>
	  * @access protected
	  * @var float
	  */
	protected $total_ventas;

	/**
	  * total_ventas_abonado
	  * 
	  * total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito<br>
	  * @access protected
	  * @var float
	  */
	protected $total_ventas_abonado;

	/**
	  * total_ventas_saldo
	  * 
	  * total de dinero que se le debe a esta sucursal por ventas a credito<br>
	  * @access protected
	  * @var float
	  */
	protected $total_ventas_saldo;

	/**
	  * total_compras
	  * 
	  * total de gastado en compras<br>
	  * @access protected
	  * @var float
	  */
	protected $total_compras;

	/**
	  * total_compras_abonado
	  * 
	  * total de abonado en compras<br>
	  * @access protected
	  * @var float
	  */
	protected $total_compras_abonado;

	/**
	  * total_gastos
	  * 
	  * total de gastos con saldo o sin salgo<br>
	  * @access protected
	  * @var float
	  */
	protected $total_gastos;

	/**
	  * total_gastos_abonado
	  * 
	  * total de gastos pagados ya<br>
	  * @access protected
	  * @var float
	  */
	protected $total_gastos_abonado;

	/**
	  * total_ingresos
	  * 
	  * total de ingresos para esta sucursal desde el ultimo corte<br>
	  * @access protected
	  * @var float
	  */
	protected $total_ingresos;

	/**
	  * total_ganancia_neta
	  * 
	  * calculo de ganancia neta<br>
	  * @access protected
	  * @var float
	  */
	protected $total_ganancia_neta;

	/**
	  * getIdCorte
	  * 
	  * Get the <i>id_corte</i> property for this object. Donde <i>id_corte</i> es identificador del corte
	  * @return int(12)
	  */
	final public function getIdCorte()
	{
		return $this->id_corte;
	}

	/**
	  * setIdCorte( $id_corte )
	  * 
	  * Set the <i>id_corte</i> property for this object. Donde <i>id_corte</i> es identificador del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>id_corte</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(12)
	  */
	final public function setIdCorte( $id_corte )
	{
		$this->id_corte = $id_corte;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de este corte
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de este corte.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getIdSucursal
	  * 
	  * Get the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal a la que se realizo este corte
	  * @return int(12)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * setIdSucursal( $id_sucursal )
	  * 
	  * Set the <i>id_sucursal</i> property for this object. Donde <i>id_sucursal</i> es sucursal a la que se realizo este corte.
	  * Una validacion basica se hara aqui para comprobar que <i>id_sucursal</i> es de tipo <i>int(12)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(12)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * getTotalVentas
	  * 
	  * Get the <i>total_ventas</i> property for this object. Donde <i>total_ventas</i> es total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas
	  * @return float
	  */
	final public function getTotalVentas()
	{
		return $this->total_ventas;
	}

	/**
	  * setTotalVentas( $total_ventas )
	  * 
	  * Set the <i>total_ventas</i> property for this object. Donde <i>total_ventas</i> es total de activo realizado en ventas para esta sucursal incluyendo ventas a credito y ventas a contado aunque no esten saldadas.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalVentas( $total_ventas )
	{
		$this->total_ventas = $total_ventas;
	}

	/**
	  * getTotalVentasAbonado
	  * 
	  * Get the <i>total_ventas_abonado</i> property for this object. Donde <i>total_ventas_abonado</i> es total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito
	  * @return float
	  */
	final public function getTotalVentasAbonado()
	{
		return $this->total_ventas_abonado;
	}

	/**
	  * setTotalVentasAbonado( $total_ventas_abonado )
	  * 
	  * Set the <i>total_ventas_abonado</i> property for this object. Donde <i>total_ventas_abonado</i> es total de efectivo adquirido gracias a ventas, incluye ventas a contado y los abonos de las ventas a credito.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalVentasAbonado( $total_ventas_abonado )
	{
		$this->total_ventas_abonado = $total_ventas_abonado;
	}

	/**
	  * getTotalVentasSaldo
	  * 
	  * Get the <i>total_ventas_saldo</i> property for this object. Donde <i>total_ventas_saldo</i> es total de dinero que se le debe a esta sucursal por ventas a credito
	  * @return float
	  */
	final public function getTotalVentasSaldo()
	{
		return $this->total_ventas_saldo;
	}

	/**
	  * setTotalVentasSaldo( $total_ventas_saldo )
	  * 
	  * Set the <i>total_ventas_saldo</i> property for this object. Donde <i>total_ventas_saldo</i> es total de dinero que se le debe a esta sucursal por ventas a credito.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ventas_saldo</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalVentasSaldo( $total_ventas_saldo )
	{
		$this->total_ventas_saldo = $total_ventas_saldo;
	}

	/**
	  * getTotalCompras
	  * 
	  * Get the <i>total_compras</i> property for this object. Donde <i>total_compras</i> es total de gastado en compras
	  * @return float
	  */
	final public function getTotalCompras()
	{
		return $this->total_compras;
	}

	/**
	  * setTotalCompras( $total_compras )
	  * 
	  * Set the <i>total_compras</i> property for this object. Donde <i>total_compras</i> es total de gastado en compras.
	  * Una validacion basica se hara aqui para comprobar que <i>total_compras</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalCompras( $total_compras )
	{
		$this->total_compras = $total_compras;
	}

	/**
	  * getTotalComprasAbonado
	  * 
	  * Get the <i>total_compras_abonado</i> property for this object. Donde <i>total_compras_abonado</i> es total de abonado en compras
	  * @return float
	  */
	final public function getTotalComprasAbonado()
	{
		return $this->total_compras_abonado;
	}

	/**
	  * setTotalComprasAbonado( $total_compras_abonado )
	  * 
	  * Set the <i>total_compras_abonado</i> property for this object. Donde <i>total_compras_abonado</i> es total de abonado en compras.
	  * Una validacion basica se hara aqui para comprobar que <i>total_compras_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalComprasAbonado( $total_compras_abonado )
	{
		$this->total_compras_abonado = $total_compras_abonado;
	}

	/**
	  * getTotalGastos
	  * 
	  * Get the <i>total_gastos</i> property for this object. Donde <i>total_gastos</i> es total de gastos con saldo o sin salgo
	  * @return float
	  */
	final public function getTotalGastos()
	{
		return $this->total_gastos;
	}

	/**
	  * setTotalGastos( $total_gastos )
	  * 
	  * Set the <i>total_gastos</i> property for this object. Donde <i>total_gastos</i> es total de gastos con saldo o sin salgo.
	  * Una validacion basica se hara aqui para comprobar que <i>total_gastos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalGastos( $total_gastos )
	{
		$this->total_gastos = $total_gastos;
	}

	/**
	  * getTotalGastosAbonado
	  * 
	  * Get the <i>total_gastos_abonado</i> property for this object. Donde <i>total_gastos_abonado</i> es total de gastos pagados ya
	  * @return float
	  */
	final public function getTotalGastosAbonado()
	{
		return $this->total_gastos_abonado;
	}

	/**
	  * setTotalGastosAbonado( $total_gastos_abonado )
	  * 
	  * Set the <i>total_gastos_abonado</i> property for this object. Donde <i>total_gastos_abonado</i> es total de gastos pagados ya.
	  * Una validacion basica se hara aqui para comprobar que <i>total_gastos_abonado</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalGastosAbonado( $total_gastos_abonado )
	{
		$this->total_gastos_abonado = $total_gastos_abonado;
	}

	/**
	  * getTotalIngresos
	  * 
	  * Get the <i>total_ingresos</i> property for this object. Donde <i>total_ingresos</i> es total de ingresos para esta sucursal desde el ultimo corte
	  * @return float
	  */
	final public function getTotalIngresos()
	{
		return $this->total_ingresos;
	}

	/**
	  * setTotalIngresos( $total_ingresos )
	  * 
	  * Set the <i>total_ingresos</i> property for this object. Donde <i>total_ingresos</i> es total de ingresos para esta sucursal desde el ultimo corte.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ingresos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalIngresos( $total_ingresos )
	{
		$this->total_ingresos = $total_ingresos;
	}

	/**
	  * getTotalGananciaNeta
	  * 
	  * Get the <i>total_ganancia_neta</i> property for this object. Donde <i>total_ganancia_neta</i> es calculo de ganancia neta
	  * @return float
	  */
	final public function getTotalGananciaNeta()
	{
		return $this->total_ganancia_neta;
	}

	/**
	  * setTotalGananciaNeta( $total_ganancia_neta )
	  * 
	  * Set the <i>total_ganancia_neta</i> property for this object. Donde <i>total_ganancia_neta</i> es calculo de ganancia neta.
	  * Una validacion basica se hara aqui para comprobar que <i>total_ganancia_neta</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalGananciaNeta( $total_ganancia_neta )
	{
		$this->total_ganancia_neta = $total_ganancia_neta;
	}

}
