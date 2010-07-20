<?php
/* Value Object file for table Compras */

class Compras
{
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
	  * id_compra
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) id de la compra
	  */
	protected $id_compra;

	/**
	  * id_proveedor
	  * @var int(11) PROVEEDOR AL QUE SE LE COMPRO
	  */
	protected $id_proveedor;

	/**
	  * tipo_compra
	  * @var enum('credito','contado') tipo de compra, contado o credito
	  */
	protected $tipo_compra;

	/**
	  * fecha
	  * @var timestamp fecha de compra
	  */
	protected $fecha;

	/**
	  * subtotal
	  * @var float subtotal de compra
	  */
	protected $subtotal;

	/**
	  * iva
	  * @var float iva de la compra
	  */
	protected $iva;

	/**
	  * id_sucursal
	  * @var int(11) sucursal en que se compro
	  */
	protected $id_sucursal;

	/**
	  * id_usuario
	  * @var int(11) quien realizo la compra
	  */
	protected $id_usuario;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getIdCompra()
	{
		return $this->id_compra;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdCompra( $id_compra )
	{
		$this->id_compra = $id_compra;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * @return enum('credito','contado')
	  */
	final public function getTipoCompra()
	{
		return $this->tipo_compra;
	}

	/**
	  * @param enum('credito','contado')
	  */
	final public function setTipoCompra( $tipo_compra )
	{
		$this->tipo_compra = $tipo_compra;
	}

	/**
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * @param timestamp
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
