<?php
/* Value Object file for table FacturaCompra */

class FacturaCompra
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->folio = $data['folio'];
			$this->id_compra = $data['id_compra'];
		}
	}

	/**
	  * folio
	  * es llave primara 
	  * @var varchar(15) Campo no documentado
	  */
	protected $folio;

	/**
	  * id_compra
	  * @var int(11) COMPRA A LA QUE CORRESPONDE LA FACTURA
	  */
	protected $id_compra;

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

}
