<?php
/* Value Object file for table DetalleCorte */

class DetalleCorte
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->num_corte = $data['num_corte'];
			$this->nombre = $data['nombre'];
			$this->total = $data['total'];
			$this->deben = $data['deben'];
		}
	}

	/**
	  * num_corte
	  * es llave primara 
	  * @var int(11) id del corte al que hace referencia
	  */
	protected $num_corte;

	/**
	  * nombre
	  * es llave primara 
	  * @var varchar(100) nombre del encargado de sucursal al momento del corte
	  */
	protected $nombre;

	/**
	  * total
	  * @var float total que le corresponde al encargado al momento del corte
	  */
	protected $total;

	/**
	  * deben
	  * @var float lo que deben en la sucursal del encargado al momento del corte
	  */
	protected $deben;

	/**
	  * es llave primara 
	  * @return int(11)
	  */
	final public function getNumCorte()
	{
		return $this->num_corte;
	}

	/**
	  * @param int(11)
	  */
	final public function setNumCorte( $num_corte )
	{
		$this->num_corte = $num_corte;
	}

	/**
	  * es llave primara 
	  * @return varchar(100)
	  */
	final public function getNombre()
	{
		return $this->nombre;
	}

	/**
	  * @param varchar(100)
	  */
	final public function setNombre( $nombre )
	{
		$this->nombre = $nombre;
	}

	/**
	  * @return float
	  */
	final public function getTotal()
	{
		return $this->total;
	}

	/**
	  * @param float
	  */
	final public function setTotal( $total )
	{
		$this->total = $total;
	}

	/**
	  * @return float
	  */
	final public function getDeben()
	{
		return $this->deben;
	}

	/**
	  * @param float
	  */
	final public function setDeben( $deben )
	{
		$this->deben = $deben;
	}

}
