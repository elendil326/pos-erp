<?php
/** Value Object file for table pagos_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
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
			$this->id_pago = $data['id_pago'];
			$this->id_venta = $data['id_venta'];
			$this->fecha = $data['fecha'];
			$this->monto = $data['monto'];
		}
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
	  * fecha
	  * 
	  * fecha de pago<br>
	  * @access protected
	  * @var date
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
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de pago
	  * @return date
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de pago.
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

}
