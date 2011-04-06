<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");
	

/**
 * Archivo que contiene la clase Comceptos la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Conceptos{

    /**
     * Array que contiene objetos de tipo Concepto
     * @var array
     */
    private $conceptos = array();

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
     * @return boolean
     */
    public function isValid() {
        return true;
    }


}

class Concepto{

    /**
     *
     * @var <type>
     */
    private $id_producto = null;

    /**
     *
     * @var <type>
     */
    private $cantidad = null;

    /**
     *
     * @var <type>
     */
    private $unidaad = null;

    /**
     *
     * @var <type>
     */
    private $descripcion = null;

    /**
     *
     * @var <type>
     */
    private $valor = null;

    /**
     *
     * @var <type> 
     */
    private $importe = null;

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