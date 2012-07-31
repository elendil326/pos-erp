<?php

require_once("generales.php");
require_once("emisor.php");
require_once("expedido_por.php");
require_once("receptor.php");
require_once("conceptos.php");
require_once("llaves.php");
require_once("success.php");
require_once("addendas.php");
require_once("impuestos.php");

/**
 * Archivo que contiene la clase Comprobante la cual provee de los medios necesarios
 * para conectarse con al web service de Caffeina, el cual obtiene las facturas electronicas,
 * ademas valida que se envien todos los datos y sean validos.
 */
class Comprobante {

    /**
     * Objeto que contiene informacion acerca de los valores generales como son
     */
    private $generales = null;

    /**
     * Establece el valor del objeto Generales
     * @param Generales $generales
     */
    public function setGenerales($generales) {
        $this->generales = $generales;
    }

    /**
     * Obtiene el valor del objeto Generales
     * @return Generales
     */
    public function getGenerales() {
        return $this->generales;
    }

    /**
     * Objeto que contiene informacion acerca de los conceptos de la venta
     */
    private $conceptos = null;

    /**
     * Establece el valor del objeto Conceptos
     * @param Conceptos $conceptos
     */
    public function setConceptos($conceptos) {
        $this->conceptos = $conceptos;
    }

    /**
     * Obtiene el valor del objeto Conceptos
     * @return Conceptos
     */
    public function getConceptos() {
        return $this->conceptos;
    }

    /**
     * Objeto que contiene informacion acerca del emisor de la factura (datos fiscales de la empresa o representante legal)
     */
    private $emisor = null;

    /**
     * Establece el valor del objeto Emisor
     * @param Emisor $emisor
     */
    public function setEmisor($emisor) {
        $this->emisor = $emisor;
    }

    /**
     * Obtiene el valor del objeto Emisor
     * @return Emisor
     */
    public function getEmisor() {
        return $this->emisor;
    }

    /**
     * Objeto que contiene informacion acerca de donde se expidio la factura
     */
    private $expedido_por = null;

    /**
     * Establece el valor del objeto ExpedidoPor
     * @param Emisor $emisor
     */
    public function setExpedidoPor($_expedido_por) {
        $this->expedido_por = $_expedido_por;
    }

    /**
     * Obtiene el valor del objeto ExpedidoPor
     * @return ExpedidoPor
     */
    public function getExpedidoPor() {
        return $this->expedido_por;
    }

    /**
     * Objeto qeu contiene informacion acerca de las llaves de conexion
     */
    private $llaves = null;

    /**
     * Establece el valor del objeto Llaves
     * @param Llaves $llaves
     */
    public function setLlaves($llaves) {
        $this->llaves = $llaves;
    }

    /**
     * Obtiene el valor del objeto Llaves
     * @return Llaves
     */
    public function getLlaves() {
        return $this->llaves;
    }

    /**
     * Objeto que contiene informacion acerca de quien es el cliente o receptor de la factura
     */
    private $receptor = null;

    /**
     * Establece el valor del objeto Receptor
     * @param Receptor $receptor
     */
    public function setReceptor($receptor) {
        $this->receptor = $receptor;
    }

    /**
     * Obtiene el valor del objeto Receptor
     * @return Receptor
     */
    public function getReceptor() {
        return $this->receptor;
    }

    /**
     * Cadena que contiene el valor del XML que se enviara al PAC.
     * @var String
     */
    private $xml_request = null;

    /**
     * Establece una cadena que contiene el XML que se enviara al PAC.
     */
    private function setXMLrequest($_xml_request) {

        /* $xml_request = str_replace("&lt;", "<", $this->getXMLrequest());
          $xml_request = str_replace("&gt;", ">", $xml_request); */

        /* $dom = new DOMDocument('1.0', 'utf-8');

          $element = $dom->createElement('factura', $_xml_request);

          // Insertamos el nuevo elemento como raíz (hijo del documento)
          $dom->appendChild($element);

          $this->xml_request = $dom->saveXML(); */

        $this->xml_request = $_xml_request;
    }

    /**
     * Obtiene una cadena que contiene el XML que se enviara al PAC.
     * @return String $xml_response
     */
    public function getXMLrequest() {
        return $this->xml_request;
    }

    //private function

    /**
     * Respuesta del PAC
     * @var String
     */
    private $xml_response = null;

    /**
     * Regresa una cadena con la respuesta del WebService sobre la peticion de la nueva factura.
     * @return String $xml_response
     */
    public function getXMLresponse() {
        return $this->xml_response;
    }

    /**
     * Establece una cadena con la respuesta del WebService sobre la peticion de la nueva factura.
     */
    private function setXMLresponse($_xml_response) {
        $this->xml_response = $_xml_response;
    }

    /**
     * Folio fiscal.
     */
    private $folio_fiscal = null;

    /**
     * Regresa una cadena que describe el folio fical de la factura.
     * @return String $xml_response
     */
    public function getFolioFiscal() {
        return $this->folio_fiscal;
    }

    /**
     * Establece una cadena que describe el folio fical de la factura.
     */
    private function setFolioFiscal($_folio_fiscal) {
        $this->folio_fiscal = $_folio_fiscal;
    }

    /**
     * Fecha y hora de certificacion 
     */
    private $fecha_certificacion = null;

    /**
     * Obtiene la fecha y hora de certificacion
     */
    public function getFechaCertificacion() {
        return $this->fecha_certificacion;
    }

    /**
     * Establece la fecha y hora de certificacion
     */
    private function setFechaCertificacion($_fecha_certificacion) {
        $this->fecha_certificacion = str_replace(array("T"), array(" "), $_fecha_certificacion);
    }

    /**
     * Numero de serie del certificado del SAT
     */
    private $numero_certificado_sat = null;

    /**
     * Obtiene el numero de serie del certificado del SAT
     */
    public function getNumeroCertificadoSAT() {
        return $this->numero_certificado_sat;
    }

    /**
     * Establece el numero de serie del certificado del SAT
     */
    public function setNumeroCertificadoSAT($_numero_certificado_sat) {
        $this->numero_certificado_sat = $_numero_certificado_sat;
    }

    /**
     * Sello digital del emisor
     */
    private $sello_digital_emisor = null;

    /**
     * Obtiene el sello digital del emisor
     */
    public function getSelloDigitalEmisor() {
        return $this->sello_digital_emisor;
    }

    /**
     * Establece el sello digital del emisor
     */
    private function setSelloDigitalEmisor($_sello_digital_emisor) {
        $this->sello_digital_emisor = $_sello_digital_emisor;
    }

    /**
     * Sello digital del sat
     */
    private $sello_digital_sat = null;

    /**
     * Obtiene el sello digital del sat
     */
    public function getSelloDigitalSAT() {
        return $this->sello_digital_sat;
    }

    /**
     * Establece el sello digital del sat
     */
    private function setSelloDigitalSAT($_sello_digital_sat) {
        $this->sello_digital_sat = $_sello_digital_sat;
    }

    /**
     * Version del timbre fiscal digital 
     */
    private $version_tfd = null;

    /**
     * Obtiene la version del timbre fiscal digital 
     */
    public function getVersionTFD() {
        return $this->version_tfd;
    }

    /**
     * Establece la version del timbre fiscal digital 
     */
    public function setVersionTFD($_version_tfd) {
        $this->version_tfd = $_version_tfd;
    }

    /**
     * UUID
     */
    private $uuid = null;

    /**
     * Obtiene el UUID
     */
    public function getUUID() {
        return $this->uuid;
    }

    /**
     * Establece el UUID
     */
    public function setUUID($_uuid) {
        $this->uuid = $_uuid;
    }

    /**
     * Contiene informacion acerca de posibles errores
     * @var String
     */
    private $error = "";

    /**
     *
     * @return <type>
     */
    public function getError() {

        return $this->error;
    }

    /**
     *
     * @param <type> $param
     */
    public function setError($param) {
        $this->error = $param;
    }

    /**
     * Objeto que informara al usuario acerca del exito de la operacion
     */
    private $success = null;
    /**
     * Url donde donde se aloja el webservice
     */
    private $url_webservice = null;

    /**
     * Establece una cadena con la direccion donde se aloja el WebService.
     */
    public function setUrlWS($_url_webservice) {
        $this->url_webservice = $_url_webservice;
    }

    /**
     *  Obtiene una cadena con la direccion donde se aloja el WebService.
     */
    private function getUrlWS() {
        return $this->url_webservice;
    }

    /**
     * cadena original del cfdi
     */
    private $cadena_original = null;

    /**
     * Obtiene la cadena original del cfdi
     */
    public function getCadenaOriginal() {
        return $this->cadena_original;
    }

    /**
     * 
     */
    private function formCadenaOriginal() {
        $this->cadena_original = "||" . $this->getVersionTFD() . "|";
        $this->cadena_original .= $this->getUUID() . "|";
        $this->cadena_original .= str_replace(array(" "), array("T"), $this->getFechaCertificacion()) . "|";
        $this->cadena_original .= $this->getSelloDigitalEmisor() . "|";
        //$this->cadena_original .= $this->getSelloDigitalSAT() . "|";        
        $this->cadena_original .= $this->getNumeroCertificadoSAT() . "||";
    }

    /**
     * Indica si esta en modo produccion
     */
    private $productionMode = POS_FACTURACION_PRODUCCION;

    /**
     * Establece informacion acerca de si el api esta en modo produccion
     * @param type $_productionMode 
     */
    private function setProductionMode($_productionMode) {
        $this->productionMode = $_productionMode;
    }

    /**
     * Obtiene informacion acerca de si el api esta en modo produccion, verdadero si esta en modo produccion, falso de lo contrario.
     * @return type 
     */
    public function getProductionMode() {
        return $this->productionMode;
    }

    /**
     * Indica si esta en modo debug.
     * Sufuncionalidad radica en que imprime en pantalla el xml request y el xml response.
     */
    private $debugMode = false;

    /**
     * Establece informacion acerca de si el api esta en modo debug
     * @param type $_debugMode 
     */
    private function setDebugMode($_debugMode) {
        $this->debugMode = $_debugMode;
    }

    /**
     * Obtiene informacion acerca de si el api esta en modo debug, verdadero si esta en modo debug, falso de lo contrario.
     * @return type 
     */
    public function getDebugMode() {
        return $this->debugMode;
    }

    /**
     * XML usado para depurar el api
     * @return type 
     */
    private function getXmlHardCode() {
        return '<?xml version="1.0" encoding="utf-8"?><cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.0" serie="A" folio="1" fecha="2011-04-03T22:55:56" sello="sellodepruebasinvalor" formaDePago="Pago en una sola exhibicion" noCertificado="00000000000000000001" certificado="certificadodepruebasinvalor" subTotal="1.00" TipoCambio="1.0" Moneda="MXN" total="1.00" tipoDeComprobante="ingreso" metodoDePago="CHEQUE" LugarExpedicion="CALERA, ZACATECAS" NumCtaPago="3266" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv3.xsd" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"><cfdi:Emisor rfc="GAGA771212GA1" nombre="GARCIA GRANADOS ARMANDO"><cfdi:DomicilioFiscal calle="FICTICIA" noExterior="101" noInterior="200 B" colonia="IMAGINARIA" municipio="TEST TOWN" estado="STATE" pais="COUNTRY" codigoPostal="01234" /></cfdi:Emisor><cfdi:Receptor rfc="PORT" nombre="PORTILLO RAMIREZ TOMAS"><cfdi:Domicilio calle="AV IMAGINARIA" noExterior="123" noInterior="LOCAL C" colonia="BIG VILLE" municipio="CITY" estado="STATE" pais="COUNTRY" codigoPostal="01234" /></cfdi:Receptor><cfdi:Conceptos><cfdi:Concepto cantidad="1" descripcion="ALGUNAS COSAS SIN IMPROTANCIA" valorUnitario="1.00" importe="1.0" /></cfdi:Conceptos><cfdi:Impuestos totalImpuestosTrasladados="0.00"><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="0.00" importe="0.00" /></cfdi:Traslados></cfdi:Impuestos><cfdi:Complemento><tfd:TimbreFiscalDigital version="1.0" UUID="A0B1C2D3-E4F5-G6H7-I8J9-0A1B2C3D4E5F" FechaTimbrado="2011-04-03T22:50:40" selloCFD="SELLODELCFD" noCertificadoSAT="00000000000000000002" selloSAT="SELLODELSAT" xsi:schemaLocation="http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/TimbreFiscalDigital/TimbreFiscalDigital.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" /></cfdi:Complemento></cfdi:Comprobante>';
        /* return '<?xml version="1.0" encoding="utf-8"?><cfdi:Comprobante xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.0" serie="A" folio="1" fecha="2011-04-03T22:55:56" sello="sellodepruebasinvalor" formaDePago="Pago en una sola exhibicion" noCertificado="00000000000000000001" certificado="certificadodepruebasinvalor" subTotal="1.00" TipoCambio="1.0" Moneda="MXN" total="1.00" tipoDeComprobante="ingreso" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv3.xsd" xmlns:cfdi="http://www.sat.gob.mx/cfd/3"><cfdi:Emisor rfc="GAGA771212GA1" nombre="GARCIA GRANADOS ARMANDO"><cfdi:DomicilioFiscal calle="FICTICIA" noExterior="101" noInterior="200 B" colonia="IMAGINARIA" municipio="TEST TOWN" estado="STATE" pais="COUNTRY" codigoPostal="01234" /></cfdi:Emisor><cfdi:Receptor rfc="PORT" nombre="PORTILLO RAMIREZ TOMAS"><cfdi:Domicilio calle="AV IMAGINARIA" noExterior="123" noInterior="LOCAL C" colonia="BIG VILLE" municipio="CITY" estado="STATE" pais="COUNTRY codigoPostal="01234" /></cfdi:Receptor><cfdi:Conceptos><cfdi:Concepto cantidad="1" descripcion="ALGUNAS COSAS SIN IMPROTANCIA" valorUnitario="1.00" importe="1.0" /></cfdi:Conceptos><cfdi:Impuestos totalImpuestosTrasladados="0.00"><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="0.00" importe="0.00" /></cfdi:Traslados></cfdi:Impuestos><cfdi:Complemento><tfd:TimbreFiscalDigital version="1.0" UUID="A0B1C2D3-E4F5-G6H7-I8J9-0A1B2C3D4E5F" FechaTimbrado="2011-04-03T22:50:40" selloCFD="SELLODELCFD" noCertificadoSAT="00000000000000000002" selloSAT="SELLODELSAT" xsi:schemaLocation="http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/TimbreFiscalDigital/TimbreFiscalDigital.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:tfd="http://www.sat.gob.mx/TimbreFiscalDigital" /></cfdi:Complemento></cfdi:Comprobante>'; */
    }

    /**
     * Construye un nuevo objeto Comprobante.
     * Valida que cada componente del comprobante sea valido, al pasar la verificacion
     * genera un archivo XML valido y se lo envia al webservice de caffeina que se encarga
     * de obtener una nueva factura electronica.
     * @return Comprobante
     */
    public function __construct() {

        //$this->setProductionMode( gethostbyname($_SERVER['REMOTE_ADDR']) == "127.0.0.1" ? false : true );
        //$this->setProductionMode( $_SERVER['HTTP_HOST'] == "pos.caffeina.mx" ? true : false );
    }

    /**
     * Valida si se leyeron correctamente los datos del sello de la factura
     * @return Success
     */
    public function isValidSelloFactura() {

        /* if ($this->getFolioFiscal() == null) {
          Logger::log("Error : No se leyo el folio fiscal de la respuesta de la factura.");
          $this->setError("Error : No se leyo el folio fiscal de la respuesta de la factura.");
          } */

        if ($this->getFechaCertificacion() == null) {
            Logger::log("Error : No se leyo la fecha de la certificacion de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        if ($this->getNumeroCertificadoSAT() == null) {
            Logger::log("Error : No se leyo la fecha de la certificacion de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        if ($this->getSelloDigitalEmisor() == null) {
            Logger::log("Error : No se leyo el sello digital del emisor de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        if ($this->getSelloDigitalSAT() == null) {
            Logger::log("Error : No se leyo el sello digital del sat de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        if ($this->getVersionTFD() == null) {
            Logger::log("Error : No se leyo la version del timbre fiscal digital de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        if ($this->getUUID() == null) {
            Logger::log("Error : No se leyo el UUID de la respuesta de la factura.");
            $this->setError("Error al generar la factura, intentelo nuevamente.");
        }

        $this->success = new Success($this->getError());

        if (!$this->success->getSuccess()) {
            Logger::log("Error : el webservice respondio a la solicitud de la factura : {$this->getXMLresponse()}");
        }

        return $this->success;
    }

    /**
     * Valida si estan correctamente formados los objetos que conforman el Comprobante
     * @return Success
     */
    public function isValid() {


        if ($this->getGenerales() == null) {
            Logger::log("Error : El objeto 'generales' es invalido");
            $this->setError("El objeto 'generales' es invalido");
        } else {
            $success = $this->getGenerales()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }

        if ($this->getConceptos() == null) {
            Logger::log("Error : El objeto 'conceptos' es invalido");
            $this->setError("El objeto 'conceptos' es invalido");
        } else {
            $success = $this->getConceptos()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }


        if ($this->getEmisor() == null) {
            Logger::log("Error : El objeto 'emisor' es invalido");
            $this->setError("El objeto 'emisor' es invalido");
        } else {
            $success = $this->getEmisor()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }


        //ESTO LO COMENTO YA QUE SI SE FACTURA DESDE EL CENTRO DE DISTRIBUCION ESTO DEBERIA DE SER NULL
        /* if ($this->getExpedidoPor() == null) {
          Logger::log("Error : El objeto 'expedido_por' es invalido");
          $this->setError("El objeto 'expedido_por' es invalido");
          } else {
          $success = $this->getExpedidoPor()->isValid();
          if (!$success->getSuccess()) {
          Logger::log($success->getInfo());
          $this->setError($success->getInfo());
          }
          } */


        if ($this->getLlaves() == null) {
            Logger::log("Error : El objeto 'llaves' es invalido");
            $this->setError("El objeto 'llaves' es invalido");
        } else {
            $success = $this->getLlaves()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }


        if ($this->getReceptor() == null) {
            Logger::log("Error : El objeto 'receptor' es invalido");
            $this->setError("El objeto 'receptor' es invalido");
        } else {
            $success = $this->getReceptor()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }

        if ($this->getUrlWS() == null) {
            Logger::log("Error : No se ha indicado la url del WebService.");
            $this->setError("Error : No se ha indicado la url del WebService.");
        }

        $this->success = new Success($this->getError());
        return $this->success;
    }

    /**
     * Realiza una peticion al WebService para que genere una nueva factura
     * @return Regresa una cadena con formato XML en caso de que se haya podido timbrar
     * la factura, de lo contrario regresa null.
     */
    function getNuevaFactura() {

        //verificamos si se construyo el comprobante correctamente
        $success = $this->isValid();

        if (!$success->getSuccess()) {
            $this->success = new Success($success->getInfo());
            return $this->success;
        } else {
            return $this->formXML();
        }

        //return $this->getResponseFromWebService();
    }

    /**
     * Construye un XML para enviarlo al WS
     * @return <type>
     */
    private function formXML() {

        //creamos la raiz del DOM DOCUMENT
        $xml = new DOMDocument('1.0', 'utf-8');

        $comprobante = $xml->createElement('comprobante');

        $comprobante->appendChild($xml->createElement('serie', $this->getGenerales()->getSerie()));

        $comprobante->appendChild($xml->createElement('folio_interno', $this->getGenerales()->getFolioInterno()));

        $comprobante->appendChild($xml->createElement('fecha', $this->getGenerales()->getFecha()));

        $comprobante->appendChild($xml->createElement('forma_de_pago', $this->getGenerales()->getFormaDePago()));
        
        $comprobante->appendChild($xml->createElement('lugar_expedicion', 'Mercado De Abastos B. Juarez BODEGA 49 Centro Celaya, Guanajuato, Mexico'));

        $comprobante->appendChild($xml->createElement('metodo_de_pago', ucfirst(strtolower($this->getGenerales()->getMetodoDePago()))));
        
        if( $this->getGenerales()->getNumeroCuenta() != null && $this->getGenerales()->getNumeroCuenta() != "" ){        
            $comprobante->appendChild($xml->createElement('numero_cuenta', $this->getGenerales()->getNumeroCuenta()));        
        }

        $comprobante->appendChild($xml->createElement('subtotal', sprintf("%01.2f", $this->getGenerales()->getSubtotal())));

        $comprobante->appendChild($xml->createElement('total', sprintf("%01.2f", $this->getGenerales()->getTotal())));

        $comprobante->appendChild($xml->createElement('iva', sprintf("%01.2f", $this->getGenerales()->getIva())));

        $emisor = $xml->createElement('emisor');

        $emisor->appendChild($xml->createElement('razon_social', $this->getEmisor()->getRazonSocial()));

        $emisor->appendChild($xml->createElement('rfc', $this->getEmisor()->getRFC()));

        $emisor->appendChild($xml->createElement('calle', $this->getEmisor()->getCalle()));

        $emisor->appendChild($xml->createElement('numero_exterior', $this->getEmisor()->getNumeroExterior()));

        if ($this->getEmisor()->getNumeroInterior() != null) {
            $emisor->appendChild($xml->createElement('numero_interior', $this->getEmisor()->getNumeroInterior()));
        } else {
            $emisor->appendChild($xml->createElement('numero_interior', ''));
        }

        $emisor->appendChild($xml->createElement('colonia', $this->getEmisor()->getColonia()));

        if ($this->getEmisor()->getLocalidad() != null) {
            $emisor->appendChild($xml->createElement('localidad', $this->getEmisor()->getLocalidad()));
        } else {
            $emisor->appendChild($xml->createElement('localidad', ''));
        }

        if ($this->getEmisor()->getReferencia() != null) {
            $emisor->appendChild($xml->createElement('referencia', $this->getEmisor()->getReferencia()));
        } else {
            $emisor->appendChild($xml->createElement('referencia', ''));
        }

        $emisor->appendChild($xml->createElement('municipio', $this->getEmisor()->getMunicipio()));

        $emisor->appendChild($xml->createElement('estado', $this->getEmisor()->getEstado()));

        $emisor->appendChild($xml->createElement('pais', $this->getEmisor()->getPais()));

        $emisor->appendChild($xml->createElement('codigo_postal', $this->getEmisor()->getCodigoPostal()));

        $comprobante->appendChild($emisor);

        $expedido_por = $xml->createElement('expedido_por');

        if ($this->getExpedidoPor() != null) {

            $expedido_por->appendChild($xml->createElement('calle', $this->getExpedidoPor()->getCalle()));

            $expedido_por->appendChild($xml->createElement('numero_exterior', $this->getExpedidoPor()->getNumeroExterior()));

            if ($this->getExpedidoPor()->getNumeroInterior() != null) {
                $expedido_por->appendChild($xml->createElement('numero_interior', $this->getExpedidoPor()->getNumeroInterior()));
            } else {
                $expedido_por->appendChild($xml->createElement('numero_interior', ''));
            }

            $expedido_por->appendChild($xml->createElement('colonia', $this->getExpedidoPor()->getColonia()));

            if ($this->getExpedidoPor()->getLocalidad() != null) {
                $expedido_por->appendChild($xml->createElement('localidad', $this->getExpedidoPor()->getLocalidad()));
            } else {
                $expedido_por->appendChild($xml->createElement('localidad', ''));
            }

            if ($this->getExpedidoPor()->getReferencia() != null) {
                $expedido_por->appendChild($xml->createElement('referencia', $this->getExpedidoPor()->getReferencia()));
            } else {
                $expedido_por->appendChild($xml->createElement('referencia', ''));
            }

            $expedido_por->appendChild($xml->createElement('municipio', $this->getExpedidoPor()->getMunicipio()));

            $expedido_por->appendChild($xml->createElement('estado', $this->getExpedidoPor()->getEstado()));

            $expedido_por->appendChild($xml->createElement('pais', $this->getExpedidoPor()->getPais()));

            $expedido_por->appendChild($xml->createElement('codigo_postal', $this->getExpedidoPor()->getCodigoPostal()));
        } else {
            $expedido_por->appendChild($xml->createElement('calle', ''));

            $expedido_por->appendChild($xml->createElement('numero_exterior', ''));

            $expedido_por->appendChild($xml->createElement('numero_interior', ''));

            $expedido_por->appendChild($xml->createElement('colonia', ''));

            $expedido_por->appendChild($xml->createElement('localidad', ''));

            $expedido_por->appendChild($xml->createElement('referencia', ''));

            $expedido_por->appendChild($xml->createElement('municipio', ''));

            $expedido_por->appendChild($xml->createElement('estado', ''));

            $expedido_por->appendChild($xml->createElement('pais', ''));

            $expedido_por->appendChild($xml->createElement('codigo_postal', ''));
        }

        $comprobante->appendChild($expedido_por);

        $receptor = $xml->createElement('receptor');

        $receptor->appendChild($xml->createElement('razon_social', $this->getReceptor()->getRazonSocial()));

        $receptor->appendChild($xml->createElement('rfc', $this->getReceptor()->getRFC()));

        $receptor->appendChild($xml->createElement('calle', $this->getReceptor()->getCalle()));

        $receptor->appendChild($xml->createElement('numero_exterior', $this->getReceptor()->getNumeroExterior()));

        if ($this->getReceptor()->getNumeroInterior() != null) {
            $receptor->appendChild($xml->createElement('numero_interior', $this->getReceptor()->getNumeroInterior()));
        } else {
            $receptor->appendChild($xml->createElement('numero_interior', ''));
        }

        $receptor->appendChild($xml->createElement('colonia', $this->getReceptor()->getColonia()));

        if ($this->getReceptor()->getLocalidad() != null) {
            $receptor->appendChild($xml->createElement('localidad', $this->getReceptor()->getLocalidad()));
        } else {
            $receptor->appendChild($xml->createElement('localidad', ''));
        }

        if ($this->getReceptor()->getReferencia() != null) {
            $receptor->appendChild($xml->createElement('referencia', $this->getReceptor()->getReferencia()));
        } else {
            $receptor->appendChild($xml->createElement('referencia', ''));
        }

        $receptor->appendChild($xml->createElement('municipio', $this->getReceptor()->getMunicipio()));

        $receptor->appendChild($xml->createElement('estado', $this->getReceptor()->getEstado()));

        $receptor->appendChild($xml->createElement('pais', $this->getReceptor()->getPais()));

        $receptor->appendChild($xml->createElement('codigo_postal', $this->getReceptor()->getCodigoPostal()));

        $comprobante->appendChild($receptor);

        $conceptos = $xml->createElement('conceptos');


        foreach ($this->getConceptos()->getConceptos() as $articulo) {

            $concepto = $xml->createElement('concepto');

            $concepto->appendChild($xml->createElement('id_producto', $articulo->getIdProducto()));

            $concepto->appendChild($xml->createElement('cantidad', sprintf("%01.2f", $articulo->getCantidad())));

            $concepto->appendChild($xml->createElement('unidad', $articulo->getUnidad()));

            $concepto->appendChild($xml->createElement('descripcion', $articulo->getDescripcion()));

            $concepto->appendChild($xml->createElement('valor_unitario', sprintf("%01.2f", $articulo->getValor())));

            $concepto->appendChild($xml->createElement('importe', sprintf("%01.2f", $articulo->getImporte())));

            $conceptos->appendChild($concepto);
        }


        $comprobante->appendChild($conceptos);

        $llaves = $xml->createElement('llaves');

        $llaves->appendChild($xml->createElement('publica', $this->getLlaves()->getPublica()));

        $llaves->appendChild($xml->createElement('privada', $this->getLlaves()->getPrivada()));

        $llaves->appendChild($xml->createElement('noCertificado', $this->getLlaves()->getNoCertificado()));

        $comprobante->appendChild($llaves);

        $xml->appendChild($comprobante);

        //guardamos en este objeto el XML que se envia al SAT
        $this->setXMLrequest($xml->saveXML());




        Logger::log("Terminado proceso de parceo de venta a XML");

        //realizamos una peticion al webservice para que genere una nueva factura
        return $this->getFacturaFromWebService();
    }

    /**
     * Realiza una petición al webservice para que genere un nuevo CFDI
     * return Boolean true en caso de tener exito, false de lo contrario
     */
    private function getFacturaFromWebService() {

        $ready_to_send = null;

        //creamos una instancia de un objeto SoapClient

        if (POS_FACTURACION_PRODUCCION)
            Logger::log("POS_FACTURACION_PRODUCCION: TRUE");
        else
            Logger::log("POS_FACTURACION_PRODUCCION: FALSE");


        if ($this->getProductionMode()) {

            $ready_to_send = $this->getXMLrequest();
            $ready_to_send = str_replace("&lt;", "<", $ready_to_send);
            $ready_to_send = str_replace("&gt;", ">", $ready_to_send);

            Logger::log("-------------- ENVIANDO ESTO ------------------- ");
            Logger::log($ready_to_send);
            Logger::log("--------------- ------------ ------------------ ");

            try {
                //If you want to dissable WSDL-caching, you can do so with 
                ini_set('soap.wsdl_cache_enabled', '0');
                ini_set('soap.wsdl_cache_ttl', '0');



                Logger::log("URL DEL WS:" . $this->getUrlWS());

                $client = new SoapClient(
                                $this->getUrlWS(),
                                array(
                                    "location" => str_replace('?wsdl', '', $this->getUrlWS())
                        ));

                //$result = $client->RececpcionComprobante(array('comprobante' => $ready_to_send));
                $result = $client->RecepcionComprobante(array('comprobante' => $ready_to_send));
            } catch (SoapFault $fault) {

                Logger::log("********** ERROR AL SOLICITAR NUEVO CFDI **********");

                Logger::log("** datos enviados **");
                Logger::log($ready_to_send);

                Logger::log(" ** informacion del error **  ");
                Logger::log("Mensaje:" . $fault->getMessage());
                Logger::log("Codigo:" . $fault->getCode());
                Logger::log("Archivo:" . $fault->getFile());
                Logger::log("Linea:" . $fault->getLine());
                Logger::log("Trace:" . $fault->getTraceAsString());

                $this->success = new Success("El servicio web que genera las facturas esta experimentando algunos problemas, intente nuevamente.");
                return $this->success;
            }

            //$response = $result->RececpcionComprobanteResult;
            $response = $result->RecepcionComprobanteResult;
        } else {
            $response = $this->getXmlHardCode();
            $ready_to_send = "Esta es solo una prueba de generacion de CFDI.";
        }

        //DEBUG
        if ($this->getDebugMode()) {

            echo "<br>------XML REQUEST-------<br>";

            $dom = new DOMDocument('1.0', 'utf-8');

            $element = $dom->createElement('request', $this->getXMLrequest());

            // Insertamos el nuevo elemento como raíz (hijo del documento)
            $dom->appendChild($element);

            echo $dom->saveXML();

            echo "<br>------XML RESPONSE-------<br>";

            $dom = new DOMDocument('1.0', 'utf-8');

            $element = $dom->createElement('response', $response);

            // Insertamos el nuevo elemento como raíz (hijo del documento)
            $dom->appendChild($element);

            echo $dom->saveXML();

            echo "<br>";
        }

        //$response = str_replace(array("cfdi:", "tfd:"), array("", ""), $response);
        //-------------------ELIMINAMOS EL NODO-------------------

        $response_en_bruto = $response;

        $response = str_replace(array("cfdi:Comprobante", "cfdi:Emisor", "cfdi:Receptor", "cfdi:Conceptos", "cfdi:Concepto", "cfdi:Impuestos", "cfdi:Complemento", "cfdi:Traslados", "cfdi:Traslado", "tfd:TimbreFiscalDigital"), array("Comprobante", "Emisor", "Receptor", "Conceptos", "Concepto", "Impuestos", "Complemento", "Traslados", "Traslado", "TimbreFiscalDigital"), $response);

        libxml_use_internal_errors(true);

        try {
            $dom = new SimpleXMLElement($response);
        } catch (Exception $e) {
            Logger::log("****************************** ERROR DE RESPUESTA CON EL WEBSERVICE ******************************\n\n El POS envio al webservice : \n\n{$ready_to_send} \n\n  La respuesta en bruto del webservice fue : \n\n{$response_en_bruto}  \n\n  Despues del tratamiento de la respuesta obtenemos : \n\n{$response}\n\n La estructura del XML de respuesta del webservice esta mal formada, error :\n\n {$e} \n\n");
            $this->success = new Success("El servicio web que genera las facturas esta experimentando algunos problemas, intente nuevamente.");
            return $this->success;
        }

        unset($dom->Complemento['success']);

        unset($dom->Complemento->cadenas);

        $response = $dom->saveXML();

        //TODO : Verificar si se puede explorar el xml ya reconstruido para almacenar de ahi los datos en la BD
        //
        //almacenamos el xml reconstruido

        $response_r = str_replace(array("/Comprobante", "<Comprobante", "Emisor", "Receptor", "Conceptos", "Concepto", "Impuestos", "Complemento", "Traslados", "Traslado", "<TimbreFiscalDigital"), array("/cfdi:Comprobante", "<cfdi:Comprobante", "cfdi:Emisor", "cfdi:Receptor", "cfdi:Conceptos", "cfdi:Concepto", "cfdi:Impuestos", "cfdi:Complemento", "cfdi:Traslados", "cfdi:Traslado", "<tfd:TimbreFiscalDigital"), $response);
        $response_r = str_replace(array("cfdi:cfdi:"), array("cfdi:"), $response_r);
        $response_r = preg_replace('/\s+(<.*?>)\s+|[\r\n]/is', '$1', $response_r);

        $this->setXMLresponse($response_r);

        //creamos el archivo
        //DEBUG
        if ($this->getDebugMode()) {

            echo "<br>------XML REQUEST-------<br>";

            $dom = new DOMDocument('1.0', 'utf-8');

            $element = $dom->createElement('request', $this->getXMLrequest());

            // Insertamos el nuevo elemento como raíz (hijo del documento)
            $dom->appendChild($element);

            echo $dom->saveXML();

            echo "<br>------XML RESPONSE-------<br>";

            $dom = new DOMDocument('1.0', 'utf-8');

            $element = $dom->createElement('response', $response_r);

            // Insertamos el nuevo elemento como raíz (hijo del documento)
            $dom->appendChild($element);

            echo $dom->saveXML();

            echo "<br>";
        }

        //--------------------------------------------------------


        $xml_response = new SimpleXMLElement($response);

        //fecha de certificacion
        $this->setFechaCertificacion($xml_response->Complemento->TimbreFiscalDigital["FechaTimbrado"]);

        //numero de certificado del sat
        $this->setNumeroCertificadoSAT($xml_response->Complemento->TimbreFiscalDigital["noCertificadoSAT"]);

        //sello digital del emisor
        $this->setSelloDigitalEmisor($xml_response->Complemento->TimbreFiscalDigital["selloCFD"]);

        //sello digital del sat
        $this->setSelloDigitalSAT($xml_response->Complemento->TimbreFiscalDigital["selloSAT"]);

        //version del timbre fiscal digital
        $this->setVersionTFD($xml_response->Complemento->TimbreFiscalDigital["version"]);

        //UUID
        $this->setUUID($xml_response->Complemento->TimbreFiscalDigital["UUID"]);

        //construye la cadena original
        $this->formCadenaOriginal();

        //TODO : que pasara si ocurre un error aqui?
        //validamos que se hayan formado bien los datos
        $success = $this->isValidSelloFactura();
        if (!$success->getSuccess()) {
            $this->setError($success->getInfo());
        }

        $this->success = new Success($this->getError());

        return $this->success;
    }

}

?>