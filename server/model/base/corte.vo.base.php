<?php
/* Value Object file for table Corte */

class Corte
{
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			$this->num_corte = $data['num_corte'];
			$this->anio = $data['anio'];
			$this->inicio = $data['inicio'];
			$this->fin = $data['fin'];
			$this->ventas = $data['ventas'];
			$this->abonosVentas = $data['abonosVentas'];
			$this->compras = $data['compras'];
			$this->AbonosCompra = $data['AbonosCompra'];
			$this->gastos = $data['gastos'];
			$this->ingresos = $data['ingresos'];
			$this->gananciasNetas = $data['gananciasNetas'];
		}
	}

	/**
	  * num_corte
	  * es llave primara 
	  * es de auto incremento 
	  * @var int(11) numero de corte
	  */
	protected $num_corte;

	/**
	  * anio
	  * @var year(4) año del corte
	  */
	protected $anio;

	/**
	  * inicio
	  * @var date año del corte
	  */
	protected $inicio;

	/**
	  * fin
	  * @var date fecha de fin del corte
	  */
	protected $fin;

	/**
	  * ventas
	  * @var float ventas al contado en ese periodo
	  */
	protected $ventas;

	/**
	  * abonosVentas
	  * @var float pagos de abonos en este periodo
	  */
	protected $abonosVentas;

	/**
	  * compras
	  * @var float compras realizadas en ese periodo
	  */
	protected $compras;

	/**
	  * AbonosCompra
	  * @var float pagos realizados en ese periodo
	  */
	protected $AbonosCompra;

	/**
	  * gastos
	  * @var float gastos echos en ese periodo
	  */
	protected $gastos;

	/**
	  * ingresos
	  * @var float ingresos obtenidos en ese periodo
	  */
	protected $ingresos;

	/**
	  * gananciasNetas
	  * @var float ganancias netas dentro del periodo
	  */
	protected $gananciasNetas;

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
	  * @return year(4)
	  */
	final public function getAnio()
	{
		return $this->anio;
	}

	/**
	  * @param year(4)
	  */
	final public function setAnio( $anio )
	{
		$this->anio = $anio;
	}

	/**
	  * @return date
	  */
	final public function getInicio()
	{
		return $this->inicio;
	}

	/**
	  * @param date
	  */
	final public function setInicio( $inicio )
	{
		$this->inicio = $inicio;
	}

	/**
	  * @return date
	  */
	final public function getFin()
	{
		return $this->fin;
	}

	/**
	  * @param date
	  */
	final public function setFin( $fin )
	{
		$this->fin = $fin;
	}

	/**
	  * @return float
	  */
	final public function getVentas()
	{
		return $this->ventas;
	}

	/**
	  * @param float
	  */
	final public function setVentas( $ventas )
	{
		$this->ventas = $ventas;
	}

	/**
	  * @return float
	  */
	final public function getAbonosVentas()
	{
		return $this->abonosVentas;
	}

	/**
	  * @param float
	  */
	final public function setAbonosVentas( $abonosVentas )
	{
		$this->abonosVentas = $abonosVentas;
	}

	/**
	  * @return float
	  */
	final public function getCompras()
	{
		return $this->compras;
	}

	/**
	  * @param float
	  */
	final public function setCompras( $compras )
	{
		$this->compras = $compras;
	}

	/**
	  * @return float
	  */
	final public function getAbonosCompra()
	{
		return $this->AbonosCompra;
	}

	/**
	  * @param float
	  */
	final public function setAbonosCompra( $AbonosCompra )
	{
		$this->AbonosCompra = $AbonosCompra;
	}

	/**
	  * @return float
	  */
	final public function getGastos()
	{
		return $this->gastos;
	}

	/**
	  * @param float
	  */
	final public function setGastos( $gastos )
	{
		$this->gastos = $gastos;
	}

	/**
	  * @return float
	  */
	final public function getIngresos()
	{
		return $this->ingresos;
	}

	/**
	  * @param float
	  */
	final public function setIngresos( $ingresos )
	{
		$this->ingresos = $ingresos;
	}

	/**
	  * @return float
	  */
	final public function getGananciasNetas()
	{
		return $this->gananciasNetas;
	}

	/**
	  * @param float
	  */
	final public function setGananciasNetas( $gananciasNetas )
	{
		$this->gananciasNetas = $gananciasNetas;
	}

}
