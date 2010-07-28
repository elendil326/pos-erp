<?php
/** Value Object file for table corte.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
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
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto Corte en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array();
		array_push($vec, array( 
		"num_corte" => $this->num_corte,
		"anio" => $this->anio,
		"inicio" => $this->inicio,
		"fin" => $this->fin,
		"ventas" => $this->ventas,
		"abonosVentas" => $this->abonosVentas,
		"compras" => $this->compras,
		"AbonosCompra" => $this->AbonosCompra,
		"gastos" => $this->gastos,
		"ingresos" => $this->ingresos,
		"gananciasNetas" => $this->gananciasNetas
		)); 
	return json_encode($vec, true); 
	}
	
	/**
	  * num_corte
	  * 
	  * numero de corte<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $num_corte;

	/**
	  * anio
	  * 
	  * año del corte<br>
	  * @access protected
	  * @var year(4)
	  */
	protected $anio;

	/**
	  * inicio
	  * 
	  * año del corte<br>
	  * @access protected
	  * @var date
	  */
	protected $inicio;

	/**
	  * fin
	  * 
	  * fecha de fin del corte<br>
	  * @access protected
	  * @var date
	  */
	protected $fin;

	/**
	  * ventas
	  * 
	  * ventas al contado en ese periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $ventas;

	/**
	  * abonosVentas
	  * 
	  * pagos de abonos en este periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $abonosVentas;

	/**
	  * compras
	  * 
	  * compras realizadas en ese periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $compras;

	/**
	  * AbonosCompra
	  * 
	  * pagos realizados en ese periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $AbonosCompra;

	/**
	  * gastos
	  * 
	  * gastos echos en ese periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $gastos;

	/**
	  * ingresos
	  * 
	  * ingresos obtenidos en ese periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $ingresos;

	/**
	  * gananciasNetas
	  * 
	  * ganancias netas dentro del periodo<br>
	  * @access protected
	  * @var float
	  */
	protected $gananciasNetas;

	/**
	  * getNumCorte
	  * 
	  * Get the <i>num_corte</i> property for this object. Donde <i>num_corte</i> es numero de corte
	  * @return int(11)
	  */
	final public function getNumCorte()
	{
		return $this->num_corte;
	}

	/**
	  * setNumCorte( $num_corte )
	  * 
	  * Set the <i>num_corte</i> property for this object. Donde <i>num_corte</i> es numero de corte.
	  * Una validacion basica se hara aqui para comprobar que <i>num_corte</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setNumCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setNumCorte( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setNumCorte( $num_corte )
	{
		$this->num_corte = $num_corte;
	}

	/**
	  * getAnio
	  * 
	  * Get the <i>anio</i> property for this object. Donde <i>anio</i> es año del corte
	  * @return year(4)
	  */
	final public function getAnio()
	{
		return $this->anio;
	}

	/**
	  * setAnio( $anio )
	  * 
	  * Set the <i>anio</i> property for this object. Donde <i>anio</i> es año del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>anio</i> es de tipo <i>year(4)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param year(4)
	  */
	final public function setAnio( $anio )
	{
		$this->anio = $anio;
	}

	/**
	  * getInicio
	  * 
	  * Get the <i>inicio</i> property for this object. Donde <i>inicio</i> es año del corte
	  * @return date
	  */
	final public function getInicio()
	{
		return $this->inicio;
	}

	/**
	  * setInicio( $inicio )
	  * 
	  * Set the <i>inicio</i> property for this object. Donde <i>inicio</i> es año del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>inicio</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	final public function setInicio( $inicio )
	{
		$this->inicio = $inicio;
	}

	/**
	  * getFin
	  * 
	  * Get the <i>fin</i> property for this object. Donde <i>fin</i> es fecha de fin del corte
	  * @return date
	  */
	final public function getFin()
	{
		return $this->fin;
	}

	/**
	  * setFin( $fin )
	  * 
	  * Set the <i>fin</i> property for this object. Donde <i>fin</i> es fecha de fin del corte.
	  * Una validacion basica se hara aqui para comprobar que <i>fin</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	final public function setFin( $fin )
	{
		$this->fin = $fin;
	}

	/**
	  * getVentas
	  * 
	  * Get the <i>ventas</i> property for this object. Donde <i>ventas</i> es ventas al contado en ese periodo
	  * @return float
	  */
	final public function getVentas()
	{
		return $this->ventas;
	}

	/**
	  * setVentas( $ventas )
	  * 
	  * Set the <i>ventas</i> property for this object. Donde <i>ventas</i> es ventas al contado en ese periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>ventas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setVentas( $ventas )
	{
		$this->ventas = $ventas;
	}

	/**
	  * getAbonosVentas
	  * 
	  * Get the <i>abonosVentas</i> property for this object. Donde <i>abonosVentas</i> es pagos de abonos en este periodo
	  * @return float
	  */
	final public function getAbonosVentas()
	{
		return $this->abonosVentas;
	}

	/**
	  * setAbonosVentas( $abonosVentas )
	  * 
	  * Set the <i>abonosVentas</i> property for this object. Donde <i>abonosVentas</i> es pagos de abonos en este periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>abonosVentas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setAbonosVentas( $abonosVentas )
	{
		$this->abonosVentas = $abonosVentas;
	}

	/**
	  * getCompras
	  * 
	  * Get the <i>compras</i> property for this object. Donde <i>compras</i> es compras realizadas en ese periodo
	  * @return float
	  */
	final public function getCompras()
	{
		return $this->compras;
	}

	/**
	  * setCompras( $compras )
	  * 
	  * Set the <i>compras</i> property for this object. Donde <i>compras</i> es compras realizadas en ese periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>compras</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setCompras( $compras )
	{
		$this->compras = $compras;
	}

	/**
	  * getAbonosCompra
	  * 
	  * Get the <i>AbonosCompra</i> property for this object. Donde <i>AbonosCompra</i> es pagos realizados en ese periodo
	  * @return float
	  */
	final public function getAbonosCompra()
	{
		return $this->AbonosCompra;
	}

	/**
	  * setAbonosCompra( $AbonosCompra )
	  * 
	  * Set the <i>AbonosCompra</i> property for this object. Donde <i>AbonosCompra</i> es pagos realizados en ese periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>AbonosCompra</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setAbonosCompra( $AbonosCompra )
	{
		$this->AbonosCompra = $AbonosCompra;
	}

	/**
	  * getGastos
	  * 
	  * Get the <i>gastos</i> property for this object. Donde <i>gastos</i> es gastos echos en ese periodo
	  * @return float
	  */
	final public function getGastos()
	{
		return $this->gastos;
	}

	/**
	  * setGastos( $gastos )
	  * 
	  * Set the <i>gastos</i> property for this object. Donde <i>gastos</i> es gastos echos en ese periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>gastos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setGastos( $gastos )
	{
		$this->gastos = $gastos;
	}

	/**
	  * getIngresos
	  * 
	  * Get the <i>ingresos</i> property for this object. Donde <i>ingresos</i> es ingresos obtenidos en ese periodo
	  * @return float
	  */
	final public function getIngresos()
	{
		return $this->ingresos;
	}

	/**
	  * setIngresos( $ingresos )
	  * 
	  * Set the <i>ingresos</i> property for this object. Donde <i>ingresos</i> es ingresos obtenidos en ese periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>ingresos</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setIngresos( $ingresos )
	{
		$this->ingresos = $ingresos;
	}

	/**
	  * getGananciasNetas
	  * 
	  * Get the <i>gananciasNetas</i> property for this object. Donde <i>gananciasNetas</i> es ganancias netas dentro del periodo
	  * @return float
	  */
	final public function getGananciasNetas()
	{
		return $this->gananciasNetas;
	}

	/**
	  * setGananciasNetas( $gananciasNetas )
	  * 
	  * Set the <i>gananciasNetas</i> property for this object. Donde <i>gananciasNetas</i> es ganancias netas dentro del periodo.
	  * Una validacion basica se hara aqui para comprobar que <i>gananciasNetas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setGananciasNetas( $gananciasNetas )
	{
		$this->gananciasNetas = $gananciasNetas;
	}

}
