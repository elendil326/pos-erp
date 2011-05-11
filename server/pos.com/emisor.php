<?php

require_once("success.php");

/**
 * Archivo que contiene la clase Emisor la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Emisor {

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Emisor";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @var <type>
     */
    private $razon_social = null;

    /**
     *
     * @return <type>
     */
    public function getRazonSocial() {

        return $this->razon_social;
    }

    /**
     *
     * @param <type> $param
     */
    public function setRazonSocial($param) {
        $this->razon_social = $param;
    }

    /**
     *
     * @var <type>
     */
    private $rfc = null;

    /**
     *
     * @return <type>
     */
    public function getRFC() {

        return $this->rfc;
    }

    /**
     *
     * @param <type> $param
     */
    public function setRFC($param) {
        $this->rfc = $param;
    }

    /**
     *
     * @var <type>
     */
    private $calle = null;

    /**
     *
     * @return <type>
     */
    public function getCalle() {
        return $this->calle;
    }

    /**
     *
     * @param <type> $param
     */
    public function setCalle($param) {
        $this->calle = $param;
    }

    /**
     *
     * @var <type>
     */
    private $numero_exterior = null;

    /**
     *
     * @return <type>
     */
    public function getNumeroExterior() {
        return $this->numero_exterior;
    }

    /**
     *
     * @param <type> $param
     */
    public function setNumeroExterior($param) {
        $this->numero_exterior = $param;
    }

    /**
     *
     * @var <type>
     */
    private $numero_interior = null;

    /**
     *
     * @return <type>
     */
    public function getNumeroInterior() {
        return $this->numero_interior;
    }

    /**
     *
     * @param <type> $param
     */
    public function setNumeroInterior($param) {
        $this->numero_interior = $param;
    }

    /**
     *
     * @var <type>
     */
    private $colonia = null;

    /**
     *
     * @return <type>
     */
    public function getColonia() {
        return $this->colonia;
    }

    /**
     *
     * @param <type> $param
     */
    public function setColonia($param) {
        $this->colonia = $param;
    }

    /**
     *
     * @var <type>
     */
    private $localidad = null;

    /**
     *
     * @return <type>
     */
    public function getLocalidad() {
        return $this->localidad;
    }

    /**
     *
     * @param <type> $param
     */
    public function setLocalidad($param) {
        $this->localidad = $param;
    }

    /**
     *
     * @var <type>
     */
    private $referencia = null;

    /**
     *
     * @return <type>
     */
    public function getReferencia() {
        return $this->referencia;
    }

    /**
     *
     * @param <type> $param
     */
    public function setReferencia($param) {
        $this->referencia = $param;
    }

    /**
     *
     * @var <type>
     */
    private $municipio = null;

    /**
     *
     * @return <type>
     */
    public function getMunicipio() {
        return $this->municipio;
    }

    /**
     *
     * @param <type> $param
     */
    public function setMunicipio($param) {
        $this->municipio = $param;
    }

    /**
     *
     * @var <type>
     */
    private $estado = null;

    /**
     *
     * @return <type>
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     *
     * @param <type> $param
     */
    public function setEstado($param) {
        $this->estado = $param;
    }

    /**
     *
     * @var <type>
     */
    private $pais = null;

    /**
     *
     * @return <type>
     */
    public function getPais() {
        return $this->pais;
    }

    /**
     *
     * @param <type> $param
     */
    public function setPais($param) {
        $this->pais = $param;
    }

    /**
     *
     * @var <type>
     */
    private $codigo_postal = null;

    /**
     *
     * @return <type>
     */
    public function getCodigoPostal() {
        return $this->codigo_postal;
    }

    /**
     *
     * @param <type> $param
     */
    public function setCodigoPostal($param) {
        $this->codigo_postal = $param;
    }

    /**
     *
     * @var <type>
     */
    private $serie = null;

    /**
     *
     * @return <type>
     */
    public function getSerie() {
        return $this->serie;
    }

    /**
     *
     * @param <type> $_serie 
     */
    public function setSerie($_serie) {
        $this->serie = $_serie;
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

        //verificamos si existe la razon social
        if (!($this->getRazonSocial() != null && $this->getRazonSocial() != "")) {
            $this->setError("No se ha definido la razon social del emisor.");
        }

        //verificampos si existe el rfc
        if (!($this->getRFC() != null && $this->getRFC() != "")) {
            $this->setError("No se ha definido el rfc del emisor.");
        } else {

            $success = $this->isValidRFC($this->getRFC());
            if (!$success->getSuccess()) {
                $this->setError($success->getInfo());
            }
        }

        //verificamos si existe la calle
        if (!($this->getCalle() != null && $this->getCalle() != "")) {
            $this->setError("No se ha definido la calle del emisor.");
        }

        //verificamos si existe el numero exterior
        if (!($this->getNumeroExterior() != null && $this->getNumeroExterior() != "")) {
            $this->setError("No se ha definido el nuemro exterior del emisor.");
        }

        //verificamos si existe la colonia
        if (!($this->getColonia() != null && $this->getColonia() != "")) {
            $this->setError("No se ha definido la colinia del emisor.");
        }

        //verificamos si existe el municipio
        if (!($this->getMunicipio() != null && $this->getMunicipio() != "")) {
            $this->setError("No se ha definido el municipio del emisor.");
        }

        //verificamos si existe el estado
        if (!($this->getEstado() != null && $this->getEstado() != "")) {
            $this->setError("No se ha definido el estado del emisor.");
        }

        //verificamos si existe el codigo postal
        if (!($this->getCodigoPostal() != null && $this->getCodigoPostal() != "")) {
            $this->setError("No se ha definido el codigo postal del emisor.");
        }

        //verificamos si existe el pais
        if (!($this->getPais() != null && $this->getPais() != "")) {
            $this->setError("No se ha definido el pais.");
        }

        $this->success = new Success($this->getError());
        return $this->success;
    }

    /**
     *
     * @param type $cadena
     * @return Success success
     */
    public function isValidRFC($cadena) {
        
        /**
         * Morales: Se compone de 3 letras seguidas por 6 dígitos y 3 caracteres alfanumericos
         * Físicas: consta de 4 letras seguida por 6 dígitos y 3 caracteres alfanumericos
         * Para hacer una longitud de 12 y 13 caracteres, las primeras letras (3 y 4) pertenecen al nombre 
         * los siguientes 6 dígitos son la fecha de nacimiento o fecha de creación. 		
         * Para las morales, y los últimos 3 perteneces a la suma de valores pertenecientes al nombre.
         */
        //validamos al longitud de la cadena
        if (!(strlen($cadena) > 11 && strlen($cadena) < 14 )) {
            $this->setError("La longitud del RFC del emisor no es valido.");
        }

        //indicara la posicion en la cual se encuentra la cadena
        $i = 0;

        //verificamos si es una persona fisica y si es asi revisamos su primer digito
        if (strlen($cadena) == 12) {
            //es persona moral, entonces agregamos un relleno al principio de la cadena para dar una longitud igual a la de la persona fisica
            $cadena = "-" . $cadena;
        } else {
            //es persona fisica y verificamos si el primer caracter es una letra
            if (is_numeric($cadena[$i])) {
                $this->setError("Formato invalido del RFC del emisor, verifique si el " . ($i + 1) . "caracter es correcto");
            }
        }

        $i = 1;

        //revisamos los 3 caracteres que deberan de ir en el RFC (para personas fisicas son 4 pero ya anteriror mente revisamos el primero)
        for ($j = $i; $j <= 3; $j++) {

            $i = $j;

            if (is_numeric($cadena[$j])) {
                $this->setError("Formato invalido el RFC del emisor, verifique si el " . ($i + 1) . "caracter es correcto");
            }
        }

        //revisamos los 6 digitos
        for ($j = 4; $j <= 9; $j++) {

            $i = $j;

            if (!is_numeric($cadena[$j])) {
                $this->setError("Formato invalido el RFC del emisor, verifique si el " . ($i + 1) . " caracter es correcto");
            }
        }

        //revisamos los 3 caracteres alfanumericos que restan	
        for ($j = 10; $j <= 12; $j++) {

            $i = $j;

            if (!ctype_alnum($cadena[$j])) {
                $this->setError("Formato invalido el RFC del emisor, verifique si el " . ($i + 1) . " caracter es correcto");
            }
        }

        $this->success = new Success($this->getError());
        return $this->success;
    }

}

?>