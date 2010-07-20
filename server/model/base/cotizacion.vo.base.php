<?php
/* Value Object file for table Cotizacion */

class Cotizacion
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->id_cotizacion = $data['id_cotizacion'];
			$this->id_cliente = $data['id_cliente'];
			$this->fecha = $data['fecha'];
			$this->subtotal = $data['subtotal'];
			$this->iva = $data['iva'];
			$this->id_sucursal = $data['id_sucursal'];
			$this->id_usuario = $data['id_usuario'];
		}
	}

	/**
	  * id_cotizacion
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id de la cotizacion
	  */
	protected $id_cotizacion;

	/**
	  * id_cliente
	  * @var int(11) id del cliente
	  */
	protected $id_cliente;

	/**
	  * fecha
	  * @var date fecha de cotizacion
	  */
	protected $fecha;

	/**
	  * subtotal
	  * @var float subtotal de la cotizacion
	  */
	protected $subtotal;

	/**
	  * iva
	  * @var float iva sobre el subtotal
	  */
	protected $iva;

	/**
	  * id_sucursal
	  * @var int(11) Campo no documentado
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * @var int(11) Campo no documentado
	  */
	protected $id_usuario;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdCotizacion()
	{
		return $this->id_cotizacion;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCotizacion( $id_cotizacion )
	{
		$this->id_cotizacion = $id_cotizacion;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCliente( $id_cliente )
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	  * @return date
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * @param date
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * @return float
	  */
	final public function getSubtotal()
	{
		return $this->subtotal;
	}

	/**
	  * @param float
	  */
	final public function setSubtotal( $subtotal )
	{
		$this->subtotal = $subtotal;
	}

	/**
	  * @return float
	  */
	final public function getIva()
	{
		return $this->iva;
	}

	/**
	  * @param float
	  */
	final public function setIva( $iva )
	{
		$this->iva = $iva;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdSucursal()
	{
		return $this->id_sucursal;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdSucursal( $id_sucursal )
	{
		$this->id_sucursal = $id_sucursal;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

}
