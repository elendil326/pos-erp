<?php

require_once("generales.php");
require_once("emisor.php");
require_once("expedido_por.php");
require_once("receptor.php");
require_once("conceptos.php");
require_once("llaves.php");
require_once("success.php");
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
     * Objeto que contiene informacion hacerca de los conceptos de la venta
     */
    private $conceptos = null;
    /**
     * Objeto que contiene informacion hacerca del emisor de la factura (datos fiscales de la empresa o representante legal)
     */
    private $emisor = null;
    /**
     * Objeto que contiene informacion hacerca de donde se expidio la factura
     */
    private $expedido_por = null;
    /**
     * Objeto qeu contiene informacion hacerca de las llaves de conexion
     */
    private $llaves = null;
    /**
     * Objeto que contiene informacion hacerca de quien es el cliente o receptor de la factura
     */
    private $receptor = null;
    /**
     * Contiene informacion hacerca de posibles errores
     */
    private $error = null;

    /**
     * Objeto que informara al usuario acerca del exito de la operacion
     */
    private $success = null;

    /**
     * Construye un nuevo objeto Comprobante.
     *
     * x
     *
     * @throws Exception Si ocurre algun error.
     */
    public function __construct($generales = null, $conceptos = null, $emisor = null, $expedido_por = null, $llaves = null, $receptor = null) {

        Logger::log("Iniciando proceso de creacion de nuevo comprobante");

        try {

            $this->generales = $generales;

            if (!$this->generales->isValid()) {
                Logger::log("Error : El objeto 'generales' es invalido");
                $this->error = "El objeto 'generales' es invalido";
                return $this;
            }

            $this->conceptos = $conceptos;

            if (!$this->conceptos->isValid()) {
                Logger::log("Error : El objeto 'conceptos' es invalido");
                $this->error = "El objeto 'conceptos' es invalido";
                return $this;
            }

            $this->emisor = $emisor;

            if (!$this->emisor->isValid()) {
                Logger::log("Error : El objeto 'emisor' es invalido");
                $this->error = "El objeto 'emisor' es invalido";
                return $this;
            }

            $this->expedido_por = $expedido_por;

            if (!$this->expedido_por->isValid()) {
                Logger::log("Error : El objeto 'expedido_por' es invalido");
                $this->error = "El objeto 'expedido_por' es invalido";
                return $this;
            }

            $this->llaves = $llaves;

            if (!$this->llaves->isValid()) {
                Logger::log("Error : El objeto 'llaves' es invalido");
                $this->error = "El objeto 'llaves' es invalido";
                return $this;
            }

            $this->receptor = $receptor;

            if (!$this->receptor->isValid()) {
                Logger::log("Error : El objeto 'receptor' es invalido");
                $this->error = "El objeto 'receptor' es invalido";
                return $this;
            }
        } catch (Exception $e) {
            Logger::log("Error : {$e}");
            $error = $e;
            return $this;
        }

        Logger::log("Terminado proceso de creacion de nuevo comprobante");
    }

//__construct

    function getNuevaFactura() {

        return null;
    }

    /**
     * Regresa un objeto que contiene informacion acerca del resultado de la operacion
     */
    public function getSuccess() {

        $this->success=new StdClass();

        if ($error != null) {

            $this->success->success = true;
            $this->success->info = "Operación realizada con éxito";
        } else {

            $this->success->success = false;
            $this->success->info = $error;
        }

        return $this;
    }

//getSuccess
}

//class
?>