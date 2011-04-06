<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");


/**
 * Archivo que contiene la clase Llaves la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Llaves{

    /**
     *
     * @var <type>
     */
    private $publica = null;

    /**
     *
     * @var <type>
     */
    private $privada = null;

    /**
     *
     * @var <type>
     */
    private $noCestificado = null;

    /**
     * Contiene informacion acerca de posibles errores
     * @var String
     */
    private $error = "";

    /**
     *
     */
    public function __construct(){


    }

    /**
     * Verifica que el objeto contenga toda la informacion necesaria
     * @return Object Success
     */
    public function isValid() {
        $this->success = new Success($this->error);
        return $this->success;
    }
	
}

?>