<?php
/** Value Object file for table factura_venta.
  * 
  * VO does not have any behaviour except for storage and retrieval of its own data (accessors and mutators).
  * @author Alan Gonzalez
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
			if( isset($data['xml']) ){
				$this->xml = $data['xml'];
			}
			if( isset($data['lugar_emision']) ){
				$this->lugar_emision = $data['lugar_emision'];
			}
			if( isset($data['tipo_comprobante']) ){
				$this->tipo_comprobante = $data['tipo_comprobante'];
			}
			if( isset($data['activa']) ){
				$this->activa = $data['activa'];
			}
			if( isset($data['sellada']) ){
				$this->sellada = $data['sellada'];
			}
			if( isset($data['forma_pago']) ){
				$this->forma_pago = $data['forma_pago'];
			}
			if( isset($data['fecha_emision']) ){
				$this->fecha_emision = $data['fecha_emision'];
			}
			if( isset($data['folio_fiscal']) ){
				$this->folio_fiscal = $data['folio_fiscal'];
			}
			if( isset($data['fecha_certificacion']) ){
				$this->fecha_certificacion = $data['fecha_certificacion'];
			}
			if( isset($data['numero_certificado_sat']) ){
				$this->numero_certificado_sat = $data['numero_certificado_sat'];
			}
			if( isset($data['sello_digital_emisor']) ){
				$this->sello_digital_emisor = $data['sello_digital_emisor'];
			}
			if( isset($data['sello_digital_sat']) ){
				$this->sello_digital_sat = $data['sello_digital_sat'];
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
			"xml" => $this->xml,
			"lugar_emision" => $this->lugar_emision,
			"tipo_comprobante" => $this->tipo_comprobante,
			"activa" => $this->activa,
			"sellada" => $this->sellada,
			"forma_pago" => $this->forma_pago,
			"fecha_emision" => $this->fecha_emision,
			"folio_fiscal" => $this->folio_fiscal,
			"fecha_certificacion" => $this->fecha_certificacion,
			"numero_certificado_sat" => $this->numero_certificado_sat,
			"sello_digital_emisor" => $this->sello_digital_emisor,
			"sello_digital_sat" => $this->sello_digital_sat
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
	  * xml
	  * 
	  * xml en bruto<br>
	  * @access protected
	  * @var text
	  */
	protected $xml;

	/**
	  * lugar_emision
	  * 
	  * id de la sucursal donde se emitio la factura<br>
	  * @access protected
	  * @var int(11)
	  */
	protected $lugar_emision;

	/**
	  * tipo_comprobante
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var enum('ingreso','egreso')
	  */
	protected $tipo_comprobante;

	/**
	  * activa
	  * 
	  * 1 indica que la factura fue emitida y esta activa, 0 que la factura fue emitida y posteriormente fue cancelada<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $activa;

	/**
	  * sellada
	  * 
	  * Indica si el WS ha timbrado la factura<br>
	  * @access protected
	  * @var tinyint(1)
	  */
	protected $sellada;

	/**
	  * forma_pago
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(100)
	  */
	protected $forma_pago;

	/**
	  * fecha_emision
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_emision;

	/**
	  * folio_fiscal
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $folio_fiscal;

	/**
	  * fecha_certificacion
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var timestamp
	  */
	protected $fecha_certificacion;

	/**
	  * numero_certificado_sat
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(128)
	  */
	protected $numero_certificado_sat;

	/**
	  * sello_digital_emisor
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(512)
	  */
	protected $sello_digital_emisor;

	/**
	  * sello_digital_sat
	  * 
	  *  [Campo no documentado]<br>
	  * @access protected
	  * @var varchar(512)
	  */
	protected $sello_digital_sat;

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
	  * getXml
	  * 
	  * Get the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto
	  * @return text
	  */
	final public function getXml()
	{
		return $this->xml;
	}

	/**
	  * setXml( $xml )
	  * 
	  * Set the <i>xml</i> property for this object. Donde <i>xml</i> es xml en bruto.
	  * Una validacion basica se hara aqui para comprobar que <i>xml</i> es de tipo <i>text</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param text
	  */
	final public function setXml( $xml )
	{
		$this->xml = $xml;
	}

	/**
	  * getLugarEmision
	  * 
	  * Get the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura
	  * @return int(11)
	  */
	final public function getLugarEmision()
	{
		return $this->lugar_emision;
	}

	/**
	  * setLugarEmision( $lugar_emision )
	  * 
	  * Set the <i>lugar_emision</i> property for this object. Donde <i>lugar_emision</i> es id de la sucursal donde se emitio la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>lugar_emision</i> es de tipo <i>int(11)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param int(11)
	  */
	final public function setLugarEmision( $lugar_emision )
	{
		$this->lugar_emision = $lugar_emision;
	}

	/**
	  * getTipoComprobante
	  * 
	  * Get the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado]
	  * @return enum('ingreso','egreso')
	  */
	final public function getTipoComprobante()
	{
		return $this->tipo_comprobante;
	}

	/**
	  * setTipoComprobante( $tipo_comprobante )
	  * 
	  * Set the <i>tipo_comprobante</i> property for this object. Donde <i>tipo_comprobante</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>tipo_comprobante</i> es de tipo <i>enum('ingreso','egreso')</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param enum('ingreso','egreso')
	  */
	final public function setTipoComprobante( $tipo_comprobante )
	{
		$this->tipo_comprobante = $tipo_comprobante;
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
	  * getSellada
	  * 
	  * Get the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura
	  * @return tinyint(1)
	  */
	final public function getSellada()
	{
		return $this->sellada;
	}

	/**
	  * setSellada( $sellada )
	  * 
	  * Set the <i>sellada</i> property for this object. Donde <i>sellada</i> es Indica si el WS ha timbrado la factura.
	  * Una validacion basica se hara aqui para comprobar que <i>sellada</i> es de tipo <i>tinyint(1)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param tinyint(1)
	  */
	final public function setSellada( $sellada )
	{
		$this->sellada = $sellada;
	}

	/**
	  * getFormaPago
	  * 
	  * Get the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado]
	  * @return varchar(100)
	  */
	final public function getFormaPago()
	{
		return $this->forma_pago;
	}

	/**
	  * setFormaPago( $forma_pago )
	  * 
	  * Set the <i>forma_pago</i> property for this object. Donde <i>forma_pago</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>forma_pago</i> es de tipo <i>varchar(100)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(100)
	  */
	final public function setFormaPago( $forma_pago )
	{
		$this->forma_pago = $forma_pago;
	}

	/**
	  * getFechaEmision
	  * 
	  * Get the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	final public function getFechaEmision()
	{
		return $this->fecha_emision;
	}

	/**
	  * setFechaEmision( $fecha_emision )
	  * 
	  * Set the <i>fecha_emision</i> property for this object. Donde <i>fecha_emision</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_emision</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaEmision( $fecha_emision )
	{
		$this->fecha_emision = $fecha_emision;
	}

	/**
	  * getFolioFiscal
	  * 
	  * Get the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	final public function getFolioFiscal()
	{
		return $this->folio_fiscal;
	}

	/**
	  * setFolioFiscal( $folio_fiscal )
	  * 
	  * Set the <i>folio_fiscal</i> property for this object. Donde <i>folio_fiscal</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>folio_fiscal</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setFolioFiscal( $folio_fiscal )
	{
		$this->folio_fiscal = $folio_fiscal;
	}

	/**
	  * getFechaCertificacion
	  * 
	  * Get the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado]
	  * @return timestamp
	  */
	final public function getFechaCertificacion()
	{
		return $this->fecha_certificacion;
	}

	/**
	  * setFechaCertificacion( $fecha_certificacion )
	  * 
	  * Set the <i>fecha_certificacion</i> property for this object. Donde <i>fecha_certificacion</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>fecha_certificacion</i> es de tipo <i>timestamp</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param timestamp
	  */
	final public function setFechaCertificacion( $fecha_certificacion )
	{
		$this->fecha_certificacion = $fecha_certificacion;
	}

	/**
	  * getNumeroCertificadoSat
	  * 
	  * Get the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado]
	  * @return varchar(128)
	  */
	final public function getNumeroCertificadoSat()
	{
		return $this->numero_certificado_sat;
	}

	/**
	  * setNumeroCertificadoSat( $numero_certificado_sat )
	  * 
	  * Set the <i>numero_certificado_sat</i> property for this object. Donde <i>numero_certificado_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>numero_certificado_sat</i> es de tipo <i>varchar(128)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(128)
	  */
	final public function setNumeroCertificadoSat( $numero_certificado_sat )
	{
		$this->numero_certificado_sat = $numero_certificado_sat;
	}

	/**
	  * getSelloDigitalEmisor
	  * 
	  * Get the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	final public function getSelloDigitalEmisor()
	{
		return $this->sello_digital_emisor;
	}

	/**
	  * setSelloDigitalEmisor( $sello_digital_emisor )
	  * 
	  * Set the <i>sello_digital_emisor</i> property for this object. Donde <i>sello_digital_emisor</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_emisor</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	final public function setSelloDigitalEmisor( $sello_digital_emisor )
	{
		$this->sello_digital_emisor = $sello_digital_emisor;
	}

	/**
	  * getSelloDigitalSat
	  * 
	  * Get the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado]
	  * @return varchar(512)
	  */
	final public function getSelloDigitalSat()
	{
		return $this->sello_digital_sat;
	}

	/**
	  * setSelloDigitalSat( $sello_digital_sat )
	  * 
	  * Set the <i>sello_digital_sat</i> property for this object. Donde <i>sello_digital_sat</i> es  [Campo no documentado].
	  * Una validacion basica se hara aqui para comprobar que <i>sello_digital_sat</i> es de tipo <i>varchar(512)</i>. 
	  * Si esta validacion falla, se arrojara... algo. 
	  * @param varchar(512)
	  */
	final public function setSelloDigitalSat( $sello_digital_sat )
	{
		$this->sello_digital_sat = $sello_digital_sat;
	}

}
