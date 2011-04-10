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
     * @return <type> 
     */
    public function getPublica(){
        return $this->publica;
    }

    /**
     *
     * @param <type> $param
     */
    public function setPublica($param){
        $this->publica = $param;
    }

    /**
     *
     * @var <type>
     */
    private $privada = null;

    /**
     *
     * @return <type>
     */
    public function getPrivada(){
        return $this->privada;
    }

    /**
     *
     * @param <type> $param
     */
    public function setPrivada($param){
        $this->privada = $param;
    }

    /**
     *
     * @var <type>
     */
    private $noCertificado = null;

    /**
     *
     * @return <type>
     */
    public function getNoCertificado(){
        return $this->noCertificado;
    }

    /**
     *
     * @param <type> $param
     */
    public function setNoCertificado($param){
        $this->noCertificado = $param;
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
    public function getError(){
        return $this->error;
    }

    /**
     *
     * @param <type> $param
     */
    public function setError($param){
        $this->error = $param;
    }

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