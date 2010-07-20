<?php
/* Value Object file for table FacturaVenta */

class FacturaVenta
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->folio = $data['folio'];
			$this->id_venta = $data['id_venta'];
		}
	}

	/**
	  * folio
	  * es llave primara 
	  * @var varchar(15) folio que tiene la factura
	  */
	protected $folio;

	/**
	  * id_venta
	  * @var int(11) venta a la cual corresponde la factura
	  */
	protected $id_venta;

	/**
	  * es llave primara 
	  * @return varchar(15)
	  */
	final public function getFolio()
	{
		return $this->folio;
	}

	/**
	  * @param varchar(15)
	  */
	final public function setFolio( $folio )
	{
		$this->folio = $folio;
	}

	/**
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

}
