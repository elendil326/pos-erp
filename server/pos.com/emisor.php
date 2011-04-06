<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");

/**
 * Archivo que contiene la clase Emisor la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Emisor{

    /**
     *
     * @var <type>
     */
    private $razon_social = null;

    /**
     *
     * @var <type>
     */
    private $rfc = null;

    /**
     *
     * @var <type>
     */
    private $calle = null;

    /**
     *
     * @var <type>
     */
    private $numero_exterior = null;

    /**
     *
     * @var <type>
     */
    private $numero_interior = null;

    /**
     *
     * @var <type>
     */
    private $colonia = null;

    /**
     *
     * @var <type>
     */
    private $localidad = null;

    /**
     *
     * @var <type>
     */
    private $referencia = null;

    /**
     *
     * @var <type>
     */
    private $municipio = null;

    /**
     *
     * @var <type>
     */
    private $estado = null;

    /**
     *
     * @var <type>
     */
    private $pais = null;

    /**
     *
     * @var <type>
     */
    private $codigo_postal = null;

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