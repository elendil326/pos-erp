<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");

/**
 * Archivo que contiene la clase Llaves la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Llaves {

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Llaves";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType(){        
        return $this->type;
    }

    /**
     * Contiene la inforamción acerca de la llave publica.
     * @var <type>
     */
    private $publica = null;

    /**
     * Obtiene la inforamción acerca de la llave publica.
     * @return <type> 
     */
    public function getPublica() {
        return $this->publica;
    }

    /**
     * Establece la inforamción acerca de la llave publica.
     * @param <type> $param
     */
    public function setPublica($param) {
        $this->publica = $param;
    }

    /**
     * Contiene la inforamción acerca de la llave privada.
     * @var <type>
     */
    private $privada = null;

    /**
     * Obtiene la inforamción acerca de la llave privada.
     * @return <type>
     */
    public function getPrivada() {
        return $this->privada;
    }

    /**
     * Establece la inforamción acerca de la llave privada.
     * @param <type> $param
     */
    public function setPrivada($param) {
        $this->privada = $param;
    }

    /**
     * Contiene la inforamción acerca del numero de certificado.
     * @var <type>
     */
    private $noCertificado = null;

    /**
     * Obtiene la inforamción acerca del numero de certificado.
     * @return <type>
     */
    public function getNoCertificado() {
        return $this->noCertificado;
    }

    /**
     * Establece la inforamción acerca del numero de certificado.
     * @param <type> $param
     */
    public function setNoCertificado($param) {
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
     *
     */
    public function __construct() {

    }

    /**
     * Verifica que el objeto contenga toda la informacion necesaria
     * @return Object Success
     */
    public function isValid() {

        //verificamos si existe el numero de certificado
        if (!(isset($this->getNoCertificado()) && $this->getNoCertificado() = !"")) {
            $this->setError("No se ha definido el numero de certificado.");
        }

        //verificamos si existe la llave privada
        if (!(isset($this->getPrivada()) && $this->getPrivada() = !"")) {
            $this->setError("No se ha definido la llave privada.");
        }

        //verificamos si existe la llave publica
        if (!(isset($this->getPublica()) && $this->getPublica() = !"")) {
            $this->setError("No se ha definido la llave publica.");
        }

        $this->success = new Success($this->error);
        return $this->success;
    }

}

?>