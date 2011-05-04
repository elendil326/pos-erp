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

//$_SERVER['DOCUMENT_ROOT'] = "C:/wamp/www/pos/trunk"

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
    public function setExpedidoPor($expedidoPor) {
        $this->expedido_por = $expedidoPor;
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
    private function setXMLrequest($xml_request) {
        $this->xml_request = $xml_request;
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
    private function setXMLresponse($xml_response) {
        $this->xml_response = $xml_response;
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
     * Construye un nuevo objeto Comprobante.
     * Valida que cada componente del comprobante sea valido, al pasar la verificacion
     * genera un archivo XML valido y se lo envia al webservice de caffeina que se encarga
     * de obtener una nueva factura electronica.
     * @return Comprobante
     */
    public function __construct() {
        
    }

//__construct

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


        if ($this->getExpedidoPor() == null) {
            Logger::log("Error : El objeto 'expedido_por' es invalido");
            $this->setError("El objeto 'expedido_por' es invalido");
        } else {
            $success = $this->getExpedidoPor()->isValid();
            if (!$success->getSuccess()) {
                Logger::log($success->getInfo());
                $this->setError($success->getInfo());
            }
        }


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

        $this->success = new Success($this->getError());
        return $this->success;
    }

    /**
     * Realiza una peticion al WebService para que genere una nueva factura
     * @return Regresa una cadena con formato XML en caso de que se haya podido timbrar
     * la factura, de lo contrario regresa null.
     */
    function getNuevaFactura() {

        return $this->formXML();

        //return $this->getResponseFromWebService();
    }

    /**
     *
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

        $comprobante->appendChild($xml->createElement('metodo_de_pago', ucfirst(strtolower($this->getGenerales()->getMetodoDePago()))));

        $comprobante->appendChild($xml->createElement('subtotal', $this->getGenerales()->getSubtotal()));

        $comprobante->appendChild($xml->createElement('total', $this->getGenerales()->getTotal()));

        $comprobante->appendChild($xml->createElement('iva', $this->getGenerales()->getIva()));

        $emisor = $xml->createElement('emisor');

        $emisor->appendChild($xml->createElement('razon_social', $this->getEmisor()->getRazonSocial()));

        $emisor->appendChild($xml->createElement('rfc', $this->getEmisor()->getRFC()));

        $emisor->appendChild($xml->createElement('calle', $this->getEmisor()->getCalle()));

        $emisor->appendChild($xml->createElement('numero_exterior', $this->getEmisor()->getNumeroExterior()));

        $emisor->appendChild($xml->createElement('numero_interior', $this->getEmisor()->getNumeroInterior()));

        $emisor->appendChild($xml->createElement('colonia', $this->getEmisor()->getColonia()));

        $emisor->appendChild($xml->createElement('localidad', $this->getEmisor()->getLocalidad()));

        $emisor->appendChild($xml->createElement('referencia', $this->getEmisor()->getReferencia()));

        $emisor->appendChild($xml->createElement('municipio', $this->getEmisor()->getMunicipio()));

        $emisor->appendChild($xml->createElement('estado', $this->getEmisor()->getEstado()));

        $emisor->appendChild($xml->createElement('pais', $this->getEmisor()->getPais()));

        $emisor->appendChild($xml->createElement('codigo_postal', $this->getEmisor()->getCodigoPostal()));

        $comprobante->appendChild($emisor);

        $expedido_por = $xml->createElement('expedido_por');

        $expedido_por->appendChild($xml->createElement('calle', $this->getExpedidoPor()->getCalle()));

        $expedido_por->appendChild($xml->createElement('numero_exterior', $this->getExpedidoPor()->getNumeroExterior()));

        $expedido_por->appendChild($xml->createElement('numero_interior', $this->getExpedidoPor()->getNumeroInterior()));

        $expedido_por->appendChild($xml->createElement('colonia', $this->getExpedidoPor()->getColonia()));

        $expedido_por->appendChild($xml->createElement('localidad', $this->getExpedidoPor()->getLocalidad()));

        $expedido_por->appendChild($xml->createElement('referencia', $this->getExpedidoPor()->getReferencia()));

        $expedido_por->appendChild($xml->createElement('municipio', $this->getExpedidoPor()->getMunicipio()));

        $expedido_por->appendChild($xml->createElement('estado', $this->getExpedidoPor()->getEstado()));

        $expedido_por->appendChild($xml->createElement('pais', $this->getExpedidoPor()->getPais()));

        $expedido_por->appendChild($xml->createElement('codigo_postal', $this->getExpedidoPor()->getCodigoPostal()));

        $comprobante->appendChild($expedido_por);

        $receptor = $xml->createElement('receptor');

        $receptor->appendChild($xml->createElement('razon_social', $this->getReceptor()->getRazonSocial()));

        $receptor->appendChild($xml->createElement('rfc', $this->getReceptor()->getRFC()));

        $receptor->appendChild($xml->createElement('calle', $this->getReceptor()->getCalle()));

        $receptor->appendChild($xml->createElement('numero_exterior', $this->getReceptor()->getNumeroExterior()));

        $receptor->appendChild($xml->createElement('numero_interior', $this->getReceptor()->getNumeroInterior()));

        $receptor->appendChild($xml->createElement('colonia', $this->getReceptor()->getColonia()));

        $receptor->appendChild($xml->createElement('localidad', $this->getReceptor()->getLocalidad()));

        $receptor->appendChild($xml->createElement('referencia', $this->getReceptor()->getReferencia()));

        $receptor->appendChild($xml->createElement('municipio', $this->getReceptor()->getMunicipio()));

        $receptor->appendChild($xml->createElement('estado', $this->getReceptor()->getEstado()));

        $receptor->appendChild($xml->createElement('pais', $this->getReceptor()->getPais()));

        $receptor->appendChild($xml->createElement('codigo_postal', $this->getReceptor()->getCodigoPostal()));

        $comprobante->appendChild($receptor);

        $conceptos = $xml->createElement('conceptos');


        foreach ($this->getConceptos()->getConceptos() as $articulo) {


            $concepto = $xml->createElement('concepto');

            $concepto->appendChild($xml->createElement('id_producto', $articulo->getIdProducto()));

            $concepto->appendChild($xml->createElement('cantidad', $articulo->getCantidad()));

            $concepto->appendChild($xml->createElement('unidad', $articulo->getUnidad()));

            $concepto->appendChild($xml->createElement('descripcion', $articulo->getDescripcion()));

            $concepto->appendChild($xml->createElement('valor_unitario', $articulo->getValor()));

            $concepto->appendChild($xml->createElement('importe', $articulo->getImporte()));

            $conceptos->appendChild($concepto);
        }


        $comprobante->appendChild($conceptos);

        $llaves = $xml->createElement('llaves');

        $llaves->appendChild($xml->createElement('publica', $this->getLlaves()->getPublica()));

        $llaves->appendChild($xml->createElement('privada', $this->getLlaves()->getPrivada()));

        $llaves->appendChild($xml->createElement('noCertificado', $this->getLlaves()->getNoCertificado()));

        $comprobante->appendChild($llaves);

        $xml->appendChild($comprobante);

        $this->setXMLrequest($xml->saveXML());

        Logger::log("Terminado proceso de parceo de venta a XML");

        //realizamos una peticion al webservice para que genere una nueva factura
        return $this->getFacturaFromWebService();
    }

    /**
     * Realiza una petición al webservice para que genere un nuevo CFDI
     * return Boolean true en casod e tener exito, false de lo contrario
     */
    private function getFacturaFromWebService() {

        //obtenemos la url del web service
        if (!( $url = PosConfigDAO::getByPK('url_timbrado') )) {
            Logger::log("Error al obtener la url del ws");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al obtener la url del web service" }');
        }

        $client = new SoapClient($url->getValue());

        //TODO : Mejorar esto
        $url_encoded = str_replace("&lt;", "<", $this->getXMLrequest());
        $url_encoded = str_replace("&gt;", ">", $url_encoded);



        //-----------------------------------

        $dom = new DOMDocument('1.0', 'utf-8');

        $element = $dom->createElement('factura', $url_encoded);

        // Insertamos el nuevo elemento como raíz (hijo del documento)
        $dom->appendChild($element);

        echo $dom->saveXML();

        //------------------------------------



        //realiza la peticion al webservice
        $result = $client->RececpcionComprobante(array('comprobante' => $url_encoded));

        //verificamos si la llamada fallo

        if (is_soap_fault($result)) {
            trigger_error("La llamada al webservice ha fallado", E_USER_ERROR);
        }

        //analizamos el success del xml

        libxml_use_internal_errors(true);

        $xml_response = new DOMDocument();

        $xml_response->loadXML($result->RececpcionComprobanteResult);

        if (!$xml_response) {
            $e = "Error cargando XML\n";
            foreach (libxml_get_errors () as $error) {
                $e.= "\t" . $error->message;
            }

            Logger::log("Error al leer xml del web service : {$e} ");
            die('{"success": false, "reason": "Error al leer xml del web service : ' . preg_quote($e) . '" }');
        }

        $params = $xml_response->getElementsByTagName('Complemento');

        $k = 0;

        foreach ($params as $param) {
            $success = $params->item($k)->getAttribute('success');
        }

        if ($success == false || $success == "false") {
            Logger::log("Error al generar el xml del web service, el webservice contesto success : false");
            $this->setError("Error al generar el xml del web service, el webservice contesto success : false");
        }

        //almacenamos la respuesta del webservice

        $this->setXMLresponse($xml_response->saveXML());

        $this->success = new Success($this->getError());


        //-----------------------------------

        /*$dom = new DOMDocument('1.0', 'utf-8');

        $element = $dom->createElement('factura', $result->RececpcionComprobanteResult);

        // Insertamos el nuevo elemento como raíz (hijo del documento)
        $dom->appendChild($element);

        echo $dom->saveXML();*/

        //------------------------------------    

        return $this->success;
    }

    /**
     * Recibe el id de un folio de una factura
     * y regresa un json con el formato necesario para generar una
     * nueva factura en formato pdf.
     */
    private function parseFacturaToJSON($xml_response) {

        //obtenemos la url del logo
        if (!( $url_logo = PosConfigDAO::getByPK('url_logo') )) {
            $url_logo = "http://t2.gstatic.com/images?q=tbn:ANd9GcTLzjmaR_M58RmjwRE_xXRziJBi68hMg898kvKtYLD1lQ22i7Br";
        }

        $json = array();

        /* array_push($json , "url" -> $url_logo);
          array_push("emisor",array(
          "razon_social"
          )); */
        /**

          {
          "url" : "string",
          "emisor": {
          "razon_social" : "string",
          "rfc": "string",
          "direccion": "string",
          "folio": "string"
          },
          "receptor": {
          "razon_social" : "string",
          "rfc": "string",
          "direccion": "string"
          },
          "datos_fiscales": {
          "numero_certificado": "string",
          "numero_aprobacion": "string",
          "anio_aprobacion": "string",
          "cadena_original": "string",
          "sello_digital": "string",
          "sello_digital_proveedor": "string",
          "pac": "string"
          },
          "factura": {
          "productos": [
          {
          "cantidad": "string",
          "descripcion": "string",
          "precio": "string",
          "importe": "string"
          }
          ],
          "subtotal": "string",
          "descuento": "string",
          "iva": "string",
          "total": "string",
          "total_letra": "string",
          "forma_pago": "string",
          "metodo_pago": "string"
          }
          }

         */
        return json_encode($json);
    }

}

//class
?>