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
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");

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
     * Respuesta del PAC
     * @var String
     */
    private $xml_response = null;

    /**
     * Regresa una cadena con la respuesta del WebService sobre la peticion de la nueva factura.
     * @return String $xml_response
     */
    private function getResponseFromWebService() {
        return $this->xml_response;
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

        $this->formXML();

        return $this->getResponseFromWebService();
    }

    private function formXML() {        

        //creamos la raiz del DOM DOCUMENT
        $xml = new DOMDocument('1.0', 'utf-8');

        $comprobante = $xml->createElement('comprobante');

        $comprobante->appendChild($xml->createElement('serie', $sucursal->getLetrasFactura()));

        $comprobante->appendChild($xml->createElement('folio_interno', $factura->getIdFolio()));

        $comprobante->appendChild($xml->createElement('fecha', date("y-m-d") . 'T' . date("H:i:s")));

        $comprobante->appendChild($xml->createElement('forma_de_pago', 'Pago en una sola exhibicion'));

        $comprobante->appendChild($xml->createElement('metodo_de_pago', ucfirst(strtolower($venta->getTipoPago()))));

        $comprobante->appendChild($xml->createElement('subtotal', $venta->getSubtotal()));

        $comprobante->appendChild($xml->createElement('total', $venta->getTotal()));

        $comprobante->appendChild($xml->createElement('iva', $venta->getIva()));

        $emisor = $xml->createElement('emisor');

        $emisor->appendChild($xml->createElement('razon_social', $sucursal->getRazonSocial()));

        $emisor->appendChild($xml->createElement('rfc', $sucursal->getRfc()));

        $emisor->appendChild($xml->createElement('calle', $sucursal->getCalle()));

        $emisor->appendChild($xml->createElement('numero_exterior', $sucursal->getNumeroExterior()));

        $emisor->appendChild($xml->createElement('numero_interior', $sucursal->getNumeroInterior()));

        $emisor->appendChild($xml->createElement('colonia', $sucursal->getColonia()));

        $emisor->appendChild($xml->createElement('localidad', $sucursal->getLocalidad()));

        $emisor->appendChild($xml->createElement('referencia', $sucursal->getReferencia()));

        $emisor->appendChild($xml->createElement('municipio', $sucursal->getMunicipio()));

        $emisor->appendChild($xml->createElement('estado', $sucursal->getEstado()));

        $emisor->appendChild($xml->createElement('pais', $sucursal->getPais()));

        $emisor->appendChild($xml->createElement('codigo_postal', $sucursal->getCodigoPostal()));

        $comprobante->appendChild($emisor);

        $expedido_por = $xml->createElement('expedido_por');

        $expedido_por->appendChild($xml->createElement('calle'));

        $expedido_por->appendChild($xml->createElement('numero_exterior'));

        $expedido_por->appendChild($xml->createElement('numero_interior'));

        $expedido_por->appendChild($xml->createElement('colonia'));

        $expedido_por->appendChild($xml->createElement('localidad'));

        $expedido_por->appendChild($xml->createElement('referencia'));

        $expedido_por->appendChild($xml->createElement('municipio'));

        $expedido_por->appendChild($xml->createElement('estado'));

        $expedido_por->appendChild($xml->createElement('pais'));

        $expedido_por->appendChild($xml->createElement('codigo_postal'));

        $comprobante->appendChild($expedido_por);

        $receptor = $xml->createElement('receptor');

        $receptor->appendChild($xml->createElement('razon_social', $cliente->getRazonSocial()));

        $receptor->appendChild($xml->createElement('rfc', $cliente->getRfc()));

        $receptor->appendChild($xml->createElement('calle', $cliente->getCalle()));

        $receptor->appendChild($xml->createElement('numero_exterior', $cliente->getNumeroExterior()));

        $receptor->appendChild($xml->createElement('numero_interior', $cliente->getNumeroInterior()));

        $receptor->appendChild($xml->createElement('colonia', $cliente->getColonia()));

        $receptor->appendChild($xml->createElement('localidad', $cliente->getLocalidad()));

        $receptor->appendChild($xml->createElement('referencia', $cliente->getReferencia()));

        $receptor->appendChild($xml->createElement('municipio', $cliente->getMunicipio()));

        $receptor->appendChild($xml->createElement('estado', $cliente->getEstado()));

        $receptor->appendChild($xml->createElement('pais', $cliente->getPais()));

        $receptor->appendChild($xml->createElement('codigo_postal', $cliente->getCodigoPostal()));

        $comprobante->appendChild($receptor);

        $conceptos = $xml->createElement('conceptos');

        foreach ($productos as $producto) {

            /*
             * verificamos si el articulo tiene algun proceso:
             *     si :
             *         verificamos si se vendio  original (case 2)
             *         verificamos si se vendio procesado (case 3)
             *         verificamos si se vendieron ambos (case 4)
             *     no :
             *         solo extraemos la descripcion y la cantidad (original) y su precio  (case 1)
             *
             */



            //creamos un objeto inventario para verificar si tiene un proceso
            if (!( $inventario = InventarioDAO::getByPK($producto->getIdProducto()) )) {
                DAO::transRollback();
                Logger::log("Error al obtener datos de la sucursal.");
                die('{"success": false, "reason": "Error al obtener datos de la sucursal." }');
            }


            $venta_original = $producto->getCantidad() > 0 ? true : false;
            $venta_procesada = $producto->getCantidadProcesada() > 0 ? true : false;
            $proceso = $inventario->getTratamiento() == "limpia" ? true : false;

            if ($venta_procesada) {

                $concepto = $xml->createElement('concepto');

                $concepto->appendChild($xml->createElement('id_producto', $producto->getIdProducto()));

                $concepto->appendChild($xml->createElement('cantidad', $producto->getCantidadProcesada() - $producto->getDescuento()));

                $concepto->appendChild($xml->createElement('unidad', $inventario->getEscala()));

                $concepto->appendChild($xml->createElement('descripcion', ucfirst(strtolower($inventario->getDescripcion() . " " . $inventario->getTratamiento()))));

                $concepto->appendChild($xml->createElement('valor', $producto->getPrecioProcesada()));

                $concepto->appendChild($xml->createElement('importe', ($producto->getCantidad() - $producto->getDescuento()) * $producto->getPrecioProcesada()));

                $conceptos->appendChild($concepto);
            }

            $concepto = $xml->createElement('concepto');

            $concepto->appendChild($xml->createElement('id_producto', $producto->getIdProducto()));

            $concepto->appendChild($xml->createElement('cantidad', $producto->getCantidad() - $producto->getDescuento()));

            $concepto->appendChild($xml->createElement('unidad', $inventario->getEscala()));

            $concepto->appendChild($xml->createElement('descripcion', $inventario->getDescripcion()));

            $concepto->appendChild($xml->createElement('valor', $producto->getPrecio()));

            $concepto->appendChild($xml->createElement('importe', ($producto->getCantidad() - $producto->getDescuento()) * $producto->getPrecio()));

            $conceptos->appendChild($concepto);
        }

        $comprobante->appendChild($conceptos);

        $llaves = $xml->createElement('llaves');

        $llaves->appendChild($xml->createElement('publica', $pos_config->publica));

        $llaves->appendChild($xml->createElement('privada', $pos_config->privada));

        $llaves->appendChild($xml->createElement('noCertificado', $pos_config->noCertificado));

        $comprobante->appendChild($llaves);

        $xml->appendChild($comprobante);
        
        Logger::log("Terminado proceso de parceo de venta a XML");
        return $xml->saveXML();
    }

    /**
     * Recibe un xml con el formato que necesita el web service para generar
     * una nueva factura electronica.
     */
    private function getFacturaFromWebService($xml_request) {

        $xml_request = utf8_encode($xml_request);

        //obtenemos la url del web service
        if (!( $url = PosConfigDAO::getByPK('url_timbrado') )) {
            Logger::log("Error al obtener la url del ws");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al obtener la url del web service" }');
        }


        $client = new SoapClient($url->getValue());

        echo (string) $xml_request;

        //var_dump($xml_request);

        $result = $client->RececpcionComprobante(array('comprobante' => $xml_request));

        echo "despues de llamar";

        //verificamos si la llamada fallo
        if (is_soap_fault($result)) {
            trigger_error("La llamada al webservice ha fallado", E_USER_ERROR);
        }

        //analizamos el success del xml


        libxml_use_internal_errors(true);



        $xml_response->loadXML($result->RececpcionComprobanteResult);

        if (!$xml_response) {
            $e = "Error cargando XML\n";
            foreach (libxml_get_errors () as $error) {
                $e.= "\t" . $error->message;
            }

            Logger::log("Error al leer xml del web service : {$e} ");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al leer xml del web service : ' . preg_quote($e) . '" }');
        }


        $params = $xml_response->getElementsByTagName('Complemento');

        $k = 0;

        foreach ($params as $param) {
            $success = $params->item($k)->getAttribute('success');
        }

        if ($success == false || $success == "false") {
            Logger::log("Error al generar el xml del web service");
            DAO::transRollback();
            die('{"success": false, "reason": "Error al generar el xml del web service" }');
        }

        return $xml_response->saveXML();
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