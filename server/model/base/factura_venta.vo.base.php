<?php
/** Value Object file for table factura_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author no author especified
  * @access public
  * @package docs
  * 
  */

class FacturaVenta extends VO
{
	/**
	  * Constructor de FacturaVenta
	  * 
	  * Para construir un objeto de tipo FacturaVenta debera llamarse a el constructor 
	  * sin parametros. Es posible, construir un objeto pasando como parametro un arreglo asociativo 
	  * cuyos campos son iguales a las variables que constituyen a este objeto.
	  * @return FacturaVenta
	  */
	function __construct( $data = NULL)
	{ 
		if(isset($data))
		{
			if( isset($data['id_folio']) ){
				$this->id_folio = $data['id_folio'];
			}
			if( isset($data['id_venta']) ){
				$this->id_venta = $data['id_venta'];
			}
			if( isset($data['id_usuario']) ){
				$this->id_usuario = $data['id_usuario'];
			}
			if( isset($data['activa']) ){
				$this->activa = $data['activa'];
			}
			if( isset($data['fecha']) ){
				$this->fecha = $data['fecha'];
			}
			if( isset($data['certificado']) ){
				$this->certificado = $data['certificado'];
			}
			if( isset($data['aprovacion']) ){
				$this->aprovacion = $data['aprovacion'];
			}
			if( isset($data['anio_aprovacion']) ){
				$this->anio_aprovacion = $data['anio_aprovacion'];
			}
			if( isset($data['cadena_original']) ){
				$this->cadena_original = $data['cadena_original'];
			}
			if( isset($data['sello_digital']) ){
				$this->sello_digital = $data['sello_digital'];
			}
			if( isset($data['sello_digital_proveedor']) ){
				$this->sello_digital_proveedor = $data['sello_digital_proveedor'];
			}
			if( isset($data['pac']) ){
				$this->pac = $data['pac'];
			}
		}
	}

	/**
	  * Obtener una representacion en String
	  * 
	  * Este metodo permite tratar a un objeto FacturaVenta en forma de cadena.
	  * La representacion de este objeto en cadena es la forma JSON (JavaScript Object Notation) para este objeto.
	  * @return String 
	  */
	public function __toString( )
	{ 
		$vec = array( 
			"id_folio" => $this->id_folio,
			"id_venta" => $this->id_venta,
			"id_usuario" => $this->id_usuario,
			"activa" => $this->activa,
			"fecha" => $this->fecha,
			"certificado" => $this->certificado,
			"aprovacion" => $this->aprovacion,
			"anio_aprovacion" => $this->anio_aprovacion,
			"cadena_original" => $this->cadena_original,
			"sello_digital" => $this->sello_digital,
			"sello_digital_proveedor" => $this->sello_digital_proveedor,
			"pac" => $this->pac
		); 
	return json_encode($vec); 
	}
	
	/**
	  * id_folio
	  * 
	  * folio que tiene la factura<br>
	  * <b>Llave Primaria</b><br>
	  * <b>Auto Incremento</b><br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_folio;

	/**
	  * id_venta
	  * 
	  * venta a la cual corresponde la factura<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $id_venta;

	/**
	  * id_usuario
	  * 
	  * Id del usuario que hiso al ultima modificacion a la factura<br>
	  * @access protected
	  * @var int(10)
	  */
	protected $id_usuario;

	/**
	  * activa
	  * 
	  * 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activa;

	/**
	  * fecha
	  * 
	  * Fecha cuando se genero esta factura<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha;

	/**
	  * certificado
	  * 
	  * sello digital, emitido por el pac<br>
	  * @access protected
	  * @var text
	  */
	protected $certificado;

	/**
	  * aprovacion
	  * 
	  * Numero de aprovacion de la factura electronica<br>
	  * @access protected
	  * @var text
	  */
	protected $aprovacion;

	/**
	  * anio_aprovacion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(10)
	  */
	protected $anio_aprovacion;

	/**
	  * cadena_original
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var text
	  */
	protected $cadena_original;

	/**
	  * sello_digital
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var text
	  */
	protected $sello_digital;

	/**
	  * sello_digital_proveedor
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var text
	  */
	protected $sello_digital_proveedor;

	/**
	  * pac
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var text
	  */
	protected $pac;

	/**
	  * getIdFolio
	  * 
	  * Get the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura
	  * @return int(11)
	  */
	final public function getIdFolio()
	{
		return $this->id_folio;
	}

	/**
	  * setIdFolio( $id_folio )
	  * 
	  * Set the <i>id_folio</i> property for this object. Donde <i>id_folio</i> es folio que tiene la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_folio</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * <br><br>Esta propiedad se mapea con un campo que es de <b>Auto Incremento</b> !<br>
	  * No deberias usar setIdFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * <br><br>Esta propiedad se mapea con un campo que es una <b>Llave Primaria</b> !<br>
	  * No deberias usar setIdFolio( ) a menos que sepas exactamente lo que estas haciendo.<br>
	  * @param int(11)
	  */
	final public function setIdFolio( $id_folio )
	{
		$this->id_folio = $id_folio;
	}

	/**
	  * getIdVenta
	  * 
	  * Get the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura
	  * @return int(11)
	  */
	final public function getIdVenta()
	{
		return $this->id_venta;
	}

	/**
	  * setIdVenta( $id_venta )
	  * 
	  * Set the <i>id_venta</i> property for this object. Donde <i>id_venta</i> es venta a la cual corresponde la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_venta</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setIdVenta( $id_venta )
	{
		$this->id_venta = $id_venta;
	}

	/**
	  * getIdUsuario
	  * 
	  * Get the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura
	  * @return int(10)
	  */
	final public function getIdUsuario()
	{
		return $this->id_usuario;
	}

	/**
	  * setIdUsuario( $id_usuario )
	  * 
	  * Set the <i>id_usuario</i> property for this object. Donde <i>id_usuario</i> es Id del usuario que hiso al ultima modificacion a la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>id_usuario</i> es de tipo <i>int(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(10)
	  */
	final public function setIdUsuario( $id_usuario )
	{
		$this->id_usuario = $id_usuario;
	}

	/**
	  * getActiva
	  * 
	  * Get the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada
	  * @return tinyint(1)
	  */
	final public function getActiva()
	{
		return $this->activa;
	}

	/**
	  * setActiva( $activa )
	  * 
	  * Set the <i>activa</i> property for this object. Donde <i>activa</i> es 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada.
	  * Una validacion basica se hara aqui para comprobar que <i>activa</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setActiva( $activa )
	{
		$this->activa = $activa;
	}

	/**
	  * getFecha
	  * 
	  * Get the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha cuando se genero esta factura
	  * @return timestamp
	  */
	final public function getFecha()
	{
		return $this->fecha;
	}

	/**
	  * setFecha( $fecha )
	  * 
	  * Set the <i>fecha</i> property for this object. Donde <i>fecha</i> es Fecha cuando se genero esta factura.
	  * Una validacion basica se hara aqui para comprobar que <i>fecha</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFecha( $fecha )
	{
		$this->fecha = $fecha;
	}

	/**
	  * getCertificado
	  * 
	  * Get the <i>certificado</i> property for this object. Donde <i>certificado</i> es sello digital, emitido por el pac
	  * @return text
	  */
	final public function getCertificado()
	{
		return $this->certificado;
	}

	/**
	  * setCertificado( $certificado )
	  * 
	  * Set the <i>certificado</i> property for this object. Donde <i>certificado</i> es sello digital, emitido por el pac.
	  * Una validacion basica se hara aqui para comprobar que <i>certificado</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setCertificado( $certificado )
	{
		$this->certificado = $certificado;
	}

	/**
	  * getAprovacion
	  * 
	  * Get the <i>aprovacion</i> property for this object. Donde <i>aprovacion</i> es Numero de aprovacion de la factura electronica
	  * @return text
	  */
	final public function getAprovacion()
	{
		return $this->aprovacion;
	}

	/**
	  * setAprovacion( $aprovacion )
	  * 
	  * Set the <i>aprovacion</i> property for this object. Donde <i>aprovacion</i> es Numero de aprovacion de la factura electronica.
	  * Una validacion basica se hara aqui para comprobar que <i>aprovacion</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setAprovacion( $aprovacion )
	{
		$this->aprovacion = $aprovacion;
	}

	/**
	  * getAnioAprovacion
	  * 
	  * Get the <i>anio_aprovacion</i> property for this object. Donde <i>anio_aprovacion</i> es  [Campo no documentado]
	  * @return varchar(10)
	  */
	final public function getAnioAprovacion()
	{
		return $this->anio_aprovacion;
	}

	/**
	  * setAnioAprovacion( $anio_aprovacion )
	  * 
	  * Set the <i>anio_aprovacion</i> property for this object. Donde <i>anio_aprovacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>anio_aprovacion</i> es de tipo <i>varchar(10)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(10)
	  */
	final public function setAnioAprovacion( $anio_aprovacion )
	{
		$this->anio_aprovacion = $anio_aprovacion;
	}

	/**
	  * getCadenaOriginal
	  * 
	  * Get the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado]
	  * @return text
	  */
	final public function getCadenaOriginal()
	{
		return $this->cadena_original;
	}

	/**
	  * setCadenaOriginal( $cadena_original )
	  * 
	  * Set the <i>cadena_original</i> property for this object. Donde <i>cadena_original</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>cadena_original</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setCadenaOriginal( $cadena_original )
	{
		$this->cadena_original = $cadena_original;
	}

	/**
	  * getSelloDigital
	  * 
	  * Get the <i>sello_digital</i> property for this object. Donde <i>sello_digital</i> es  [Campo no documentado]
	  * @return text
	  */
	final public function getSelloDigital()
	{
		return $this->sello_digital;
	}

	/**
	  * setSelloDigital( $sello_digital )
	  * 
	  * Set the <i>sello_digital</i> property for this object. Donde <i>sello_digital</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setSelloDigital( $sello_digital )
	{
		$this->sello_digital = $sello_digital;
	}

	/**
	  * getSelloDigitalProveedor
	  * 
	  * Get the <i>sello_digital_proveedor</i> property for this object. Donde <i>sello_digital_proveedor</i> es  [Campo no documentado]
	  * @return text
	  */
	final public function getSelloDigitalProveedor()
	{
		return $this->sello_digital_proveedor;
	}

	/**
	  * setSelloDigitalProveedor( $sello_digital_proveedor )
	  * 
	  * Set the <i>sello_digital_proveedor</i> property for this object. Donde <i>sello_digital_proveedor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_proveedor</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setSelloDigitalProveedor( $sello_digital_proveedor )
	{
		$this->sello_digital_proveedor = $sello_digital_proveedor;
	}

	/**
	  * getPac
	  * 
	  * Get the <i>pac</i> property for this object. Donde <i>pac</i> es  [Campo no documentado]
	  * @return text
	  */
	final public function getPac()
	{
		return $this->pac;
	}

	/**
	  * setPac( $pac )
	  * 
	  * Set the <i>pac</i> property for this object. Donde <i>pac</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>pac</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setPac( $pac )
	{
		$this->pac = $pac;
	}

}
