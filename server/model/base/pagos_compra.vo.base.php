<?php
/** Value Object file for table pagos_compra.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * 
  */

class PagosCompra
{
	/**
	  * Constructor de PagosCompra
	  * 
	  * Para construir un objeto de tipo PagosCompra debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return PagosCompra
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_pago = $data['id_pago'];
			$this->id_compra = $data['id_compra'];
			$this->fecha = $data['fecha'];
			$this->monto = $data['monto'];
		}
	}

	/**
	  * id_pago
	  * 
	  * identificador del pago<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_pago;

	/**
	  * id_compra
	  * 
	  * identificador de la compra a la que pagamos<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra;

	/**
	  * fecha
	  * 
	  * fecha en que se abono<br>
	  * @access protected
	  * @var date
	  */
	protected $fecha;

	/**
	  * monto
	  * 
	  * monto que se abono<br>
	  * @access protected
	  * @var float
	  */
	protected $monto;

	/**
	  * getIdPago
	  * 
	  * Get the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es identificador del pago
	  * @return int(11)
	  */
	final public function getIdPago()
	{
		return $this->id_pago;
	}

	/**
	  * setIdPago( $id_pago )
	  * 
	  * Set the <i>id_pago</i> property for this object. Donde <i>id_pago</i> es identificador del pago.
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
	  * getIdCompra
	  * 
	  * Get the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es identificador de la compra a la que pagamos
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * setIdCompra( $id_compra )
	  * 
	  * Set the <i>id_compra</i> property for this object. Donde <i>id_compra</i> es identificador de la compra a la que pagamos.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en que se abono
	  * @return date
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha en que se abono.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getMonto
	  * 
	  * Get the <i>monto</i> property for this object. Donde <i>monto</i> es monto que se abono
	  * @return float
	  */
	final public function getMonto()
	{
		return $this->monto;
	}

	/**
	  * setMonto( $monto )
	  * 
	  * Set the <i>monto</i> property for this object. Donde <i>monto</i> es monto que se abono.
	  * Una validacion basica se hara aqui para comprobar que <i>monto</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMonto( $monto )
	{
		$this->monto = $monto;
	}

}
