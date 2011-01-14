<?php
/** Value Object file for table compra_proveedor.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez <alan@caffeina.mx> 
  * @access public
  * @package docs
  * 
  */

class CompraProveedor extends VO
{
	/**
	  * Constructor de CompraProveedor
	  * 
	  * Para construir un objeto de tipo CompraProveedor debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return CompraProveedor
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_compra_proveedor']) ){
				$this->id_compra_proveedor = $data['id_compra_proveedor'];
			}
			if( isset($data['peso_origen']) ){
				$this->peso_origen = $data['peso_origen'];
			}
			if( isset($data['id_proveedor']) ){
				$this->id_proveedor = $data['id_proveedor'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['fecha_origen']) ){
				$this->fecha_origen = $data['fecha_origen'];
			}
			if( isset($data['folio']) ){
				$this->folio = $data['folio'];
			}
			if( isset($data['numero_de_viaje']) ){
				$this->numero_de_viaje = $data['numero_de_viaje'];
			}
			if( isset($data['peso_recibido']) ){
				$this->peso_recibido = $data['peso_recibido'];
			}
			if( isset($data['arpillas']) ){
				$this->arpillas = $data['arpillas'];
			}
			if( isset($data['peso_por_arpilla']) ){
				$this->peso_por_arpilla = $data['peso_por_arpilla'];
			}
			if( isset($data['productor']) ){
				$this->productor = $data['productor'];
			}
			if( isset($data['calidad']) ){
				$this->calidad = $data['calidad'];
			}
			if( isset($data['merma_por_arpilla']) ){
				$this->merma_por_arpilla = $data['merma_por_arpilla'];
			}
			if( isset($data['total_origen']) ){
				$this->total_origen = $data['total_origen'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto CompraProveedor en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_compra_proveedor" => $this->id_compra_proveedor,
			"peso_origen" => $this->peso_origen,
			"id_proveedor" => $this->id_proveedor,
			"fecha" => $this->fecha,
			"fecha_origen" => $this->fecha_origen,
			"folio" => $this->folio,
			"numero_de_viaje" => $this->numero_de_viaje,
			"peso_recibido" => $this->peso_recibido,
			"arpillas" => $this->arpillas,
			"peso_por_arpilla" => $this->peso_por_arpilla,
			"productor" => $this->productor,
			"calidad" => $this->calidad,
			"merma_por_arpilla" => $this->merma_por_arpilla,
			"total_origen" => $this->total_origen
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_compra_proveedor
	  * 
	  * identificador de la compra<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_compra_proveedor;

	/**
	  * peso_origen
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $peso_origen;

	/**
	  * id_proveedor
	  * 
	  * id del proveedor a quien se le hizo esta compra<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_proveedor;

	/**
	  * fecha
	  * 
	  * fecha de cuando se recibio el embarque<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * fecha_origen
	  * 
	  * fecha de cuando se envio este embarque<br>
	  * @access protected
	  * @var date
	  */
	protected $fecha_origen;

	/**
	  * folio
	  * 
	  * folio de la remision<br>
	  * @access protected
	  * @var varchar(11)
	  */
	protected $folio;

	/**
	  * numero_de_viaje
	  * 
	  * numero de viaje<br>
	  * @access protected
	  * @var varchar(11)
	  */
	protected $numero_de_viaje;

	/**
	  * peso_recibido
	  * 
	  * peso en kilogramos reportado en la remision<br>
	  * @access protected
	  * @var float
	  */
	protected $peso_recibido;

	/**
	  * arpillas
	  * 
	  * numero de arpillas en el camion<br>
	  * @access protected
	  * @var float
	  */
	protected $arpillas;

	/**
	  * peso_por_arpilla
	  * 
	  * peso promedio en kilogramos por arpilla<br>
	  * @access protected
	  * @var float
	  */
	protected $peso_por_arpilla;

	/**
	  * productor
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(64)
	  */
	protected $productor;

	/**
	  * calidad
	  * 
	  * Describe la calidad del producto asignando una calificacion en eel rango de 0-100<br>
	  * @access protected
	  * @var tinyint(3)
	  */
	protected $calidad;

	/**
	  * merma_por_arpilla
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var float
	  */
	protected $merma_por_arpilla;

	/**
	  * total_origen
	  * 
	  * Es lo que vale el embarque segun el proveedor<br>
	  * @access protected
	  * @var float
	  */
	protected $total_origen;

	/**
	  * getIdCompraProveedor
	  * 
	  * Get the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es identificador de la compra
	  * @return int(11)
	  */
	final public function getIdCompraProveedor()
	{
		return $this->id_compra_proveedor;
	}

	/**
	  * setIdCompraProveedor( $id_compra_proveedor )
	  * 
	  * Set the <i>id_compra_proveedor</i> property for this object. Donde <i>id_compra_proveedor</i> es identificador de la compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_compra_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdCompraProveedor( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdCompraProveedor( $id_compra_proveedor )
	{
		$this->id_compra_proveedor = $id_compra_proveedor;
	}

	/**
	  * getPesoOrigen
	  * 
	  * Get the <i>peso_origen</i> property for this object. Donde <i>peso_origen</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getPesoOrigen()
	{
		return $this->peso_origen;
	}

	/**
	  * setPesoOrigen( $peso_origen )
	  * 
	  * Set the <i>peso_origen</i> property for this object. Donde <i>peso_origen</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>peso_origen</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPesoOrigen( $peso_origen )
	{
		$this->peso_origen = $peso_origen;
	}

	/**
	  * getIdProveedor
	  * 
	  * Get the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es id del proveedor a quien se le hizo esta compra
	  * @return int(11)
	  */
	final public function getIdProveedor()
	{
		return $this->id_proveedor;
	}

	/**
	  * setIdProveedor( $id_proveedor )
	  * 
	  * Set the <i>id_proveedor</i> property for this object. Donde <i>id_proveedor</i> es id del proveedor a quien se le hizo esta compra.
	  * Una validacion basica se hara aqui para comprobar que <i>id_proveedor</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdProveedor( $id_proveedor )
	{
		$this->id_proveedor = $id_proveedor;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cuando se recibio el embarque
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es fecha de cuando se recibio el embarque.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getFechaOrigen
	  * 
	  * Get the <i>fecha_origen</i> property for this object. Donde <i>fecha_origen</i> es fecha de cuando se envio este embarque
	  * @return date
	  */
	final public function getFechaOrigen()
	{
		return $this->fecha_origen;
	}

	/**
	  * setFechaOrigen( $fecha_origen )
	  * 
	  * Set the <i>fecha_origen</i> property for this object. Donde <i>fecha_origen</i> es fecha de cuando se envio este embarque.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_origen</i> es de tipo <i>date</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param date
	  */
	final public function setFechaOrigen( $fecha_origen )
	{
		$this->fecha_origen = $fecha_origen;
	}

	/**
	  * getFolio
	  * 
	  * Get the <i>folio</i> property for this object. Donde <i>folio</i> es folio de la remision
	  * @return varchar(11)
	  */
	final public function getFolio()
	{
		return $this->folio;
	}

	/**
	  * setFolio( $folio )
	  * 
	  * Set the <i>folio</i> property for this object. Donde <i>folio</i> es folio de la remision.
	  * Una validacion basica se hara aqui para comprobar que <i>folio</i> es de tipo <i>varchar(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(11)
	  */
	final public function setFolio( $folio )
	{
		$this->folio = $folio;
	}

	/**
	  * getNumeroDeViaje
	  * 
	  * Get the <i>numero_de_viaje</i> property for this object. Donde <i>numero_de_viaje</i> es numero de viaje
	  * @return varchar(11)
	  */
	final public function getNumeroDeViaje()
	{
		return $this->numero_de_viaje;
	}

	/**
	  * setNumeroDeViaje( $numero_de_viaje )
	  * 
	  * Set the <i>numero_de_viaje</i> property for this object. Donde <i>numero_de_viaje</i> es numero de viaje.
	  * Una validacion basica se hara aqui para comprobar que <i>numero_de_viaje</i> es de tipo <i>varchar(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(11)
	  */
	final public function setNumeroDeViaje( $numero_de_viaje )
	{
		$this->numero_de_viaje = $numero_de_viaje;
	}

	/**
	  * getPesoRecibido
	  * 
	  * Get the <i>peso_recibido</i> property for this object. Donde <i>peso_recibido</i> es peso en kilogramos reportado en la remision
	  * @return float
	  */
	final public function getPesoRecibido()
	{
		return $this->peso_recibido;
	}

	/**
	  * setPesoRecibido( $peso_recibido )
	  * 
	  * Set the <i>peso_recibido</i> property for this object. Donde <i>peso_recibido</i> es peso en kilogramos reportado en la remision.
	  * Una validacion basica se hara aqui para comprobar que <i>peso_recibido</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPesoRecibido( $peso_recibido )
	{
		$this->peso_recibido = $peso_recibido;
	}

	/**
	  * getArpillas
	  * 
	  * Get the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es numero de arpillas en el camion
	  * @return float
	  */
	final public function getArpillas()
	{
		return $this->arpillas;
	}

	/**
	  * setArpillas( $arpillas )
	  * 
	  * Set the <i>arpillas</i> property for this object. Donde <i>arpillas</i> es numero de arpillas en el camion.
	  * Una validacion basica se hara aqui para comprobar que <i>arpillas</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setArpillas( $arpillas )
	{
		$this->arpillas = $arpillas;
	}

	/**
	  * getPesoPorArpilla
	  * 
	  * Get the <i>peso_por_arpilla</i> property for this object. Donde <i>peso_por_arpilla</i> es peso promedio en kilogramos por arpilla
	  * @return float
	  */
	final public function getPesoPorArpilla()
	{
		return $this->peso_por_arpilla;
	}

	/**
	  * setPesoPorArpilla( $peso_por_arpilla )
	  * 
	  * Set the <i>peso_por_arpilla</i> property for this object. Donde <i>peso_por_arpilla</i> es peso promedio en kilogramos por arpilla.
	  * Una validacion basica se hara aqui para comprobar que <i>peso_por_arpilla</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setPesoPorArpilla( $peso_por_arpilla )
	{
		$this->peso_por_arpilla = $peso_por_arpilla;
	}

	/**
	  * getProductor
	  * 
	  * Get the <i>productor</i> property for this object. Donde <i>productor</i> es  [Campo no documentado]
	  * @return varchar(64)
	  */
	final public function getProductor()
	{
		return $this->productor;
	}

	/**
	  * setProductor( $productor )
	  * 
	  * Set the <i>productor</i> property for this object. Donde <i>productor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>productor</i> es de tipo <i>varchar(64)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(64)
	  */
	final public function setProductor( $productor )
	{
		$this->productor = $productor;
	}

	/**
	  * getCalidad
	  * 
	  * Get the <i>calidad</i> property for this object. Donde <i>calidad</i> es Describe la calidad del producto asignando una calificacion en eel rango de 0-100
	  * @return tinyint(3)
	  */
	final public function getCalidad()
	{
		return $this->calidad;
	}

	/**
	  * setCalidad( $calidad )
	  * 
	  * Set the <i>calidad</i> property for this object. Donde <i>calidad</i> es Describe la calidad del producto asignando una calificacion en eel rango de 0-100.
	  * Una validacion basica se hara aqui para comprobar que <i>calidad</i> es de tipo <i>tinyint(3)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(3)
	  */
	final public function setCalidad( $calidad )
	{
		$this->calidad = $calidad;
	}

	/**
	  * getMermaPorArpilla
	  * 
	  * Get the <i>merma_por_arpilla</i> property for this object. Donde <i>merma_por_arpilla</i> es  [Campo no documentado]
	  * @return float
	  */
	final public function getMermaPorArpilla()
	{
		return $this->merma_por_arpilla;
	}

	/**
	  * setMermaPorArpilla( $merma_por_arpilla )
	  * 
	  * Set the <i>merma_por_arpilla</i> property for this object. Donde <i>merma_por_arpilla</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>merma_por_arpilla</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setMermaPorArpilla( $merma_por_arpilla )
	{
		$this->merma_por_arpilla = $merma_por_arpilla;
	}

	/**
	  * getTotalOrigen
	  * 
	  * Get the <i>total_origen</i> property for this object. Donde <i>total_origen</i> es Es lo que vale el embarque segun el proveedor
	  * @return float
	  */
	final public function getTotalOrigen()
	{
		return $this->total_origen;
	}

	/**
	  * setTotalOrigen( $total_origen )
	  * 
	  * Set the <i>total_origen</i> property for this object. Donde <i>total_origen</i> es Es lo que vale el embarque segun el proveedor.
	  * Una validacion basica se hara aqui para comprobar que <i>total_origen</i> es de tipo <i>float</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param float
	  */
	final public function setTotalOrigen( $total_origen )
	{
		$this->total_origen = $total_origen;
	}

}
