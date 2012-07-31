<?php

require_once("success.php");

/**
 * Archivo que contiene la clase Generales la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Generales {

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Generales";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Contiene la informacion acerca de la serie de la factura.
     * @var String
     */
    private $serie = null;

    /**
     * Establece la informacion acerca de la serie de la factura.
     * @param <type> $param 
     */
    public function setSerie($param) {
        $this->serie = $param;
    }

    /**
     * Obtiene la informacion acerca de la serie de la factura.
     * @return <type> 
     */
    public function getSerie() {
        return $this->serie;
    }

    /**
     * Contiene la informacion acerca del folio interno de la factura.
     * @var <type>
     */
    private $folio_interno = null;

    /**
     * Establece la informacion acerca del folio interno de la factura.
     * @param <type> $param
     */
    public function setFolioInterno($param) {
        $this->folio_interno = $param;
    }

    /**
     * Obtiene la informacion acerca del folio interno de la factura.
     * @return <type>
     */
    public function getFolioInterno() {
        return $this->folio_interno;
    }

    /**
     * Contiene la informacion acerca de la fecha de emision de la factura.
     * @var <type>
     */
    private $fecha = null;

    /**
     * Establece la informacion acerca de la fecha de emision de la factura.
     * @param <type> $param
     */
    public function setFecha($param) {
        $this->fecha = $param;
    }

    /**
     * Obtiene la informacion acerca de la fecha de emision de la factura.
     * @return <type>
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Contiene la informacion acerca de la forma de pago de la factura.
     * @var <type>
     */
    private $forma_de_pago = null;

    /**
     * Establece la informacion acerca de la forma de pago de la factura.
     * @param <type> $param
     */
    public function setFormaDePago($param) {
        $this->forma_de_pago = $param;
    }

    /**
     * Obtiene la informacion acerca de la forma de pago de la factura.
     * @return <type>
     */
    public function getFormaDePago() {
        return $this->forma_de_pago;
    }

    /**
     * Contiene la informacion acerca del metodo de pago de la factura.
     * @var <type>
     */
    private $metodo_de_pago = null;

    /**
     * Establece la informacion acerca del metodo de pago de la factura.
     * @param <type> $param
     */
    public function setMetodoDePago($param) {
        $this->metodo_de_pago = $param;
    }

    /**
     * Obtiene  la información acerca del metodo de pago de la factura.
     * @return <type>
     */
    public function getMetodoDePago() {
        return $this->metodo_de_pago;
    }

    /**
     * Contiene la información acerca del subtotal de la factura.
     * @var <type>
     */
    private $subtotal = null;

    /**
     * Establece la información acerca del subtotal de la factura.
     * @param <type> $param
     */
    public function setSubtotal($param) {
        $this->subtotal = $param;
    }

    /**
     * Obtiene la información acerca del subtotal de la factura.
     * @return <type>
     */
    public function getSubtotal() {
        return $this->subtotal;
    }

    /**
     * Contiene la información acerca del total de la factura.
     * @var <type>
     */
    private $total = null;

    /**
     * Estableca la información acerca del total de la factura.
     * @param <type> $param
     */
    public function setTotal($param) {
        $this->total = $param;
    }

    /**
     * Obtiene la información acerca del total de la factura.
     * @return <type>
     */
    public function getTotal() {
        return $this->total;
    }

    /**
     * Contiene la información acerca del iva de la factura.
     * @var <type> 
     */
    private $iva = null;

    /**
     * Establece la información acerca del iva de la factura.
     * @param <type> $param
     */
    public function setIva($param) {
        $this->iva = $param;
    }

    /**
     * Obtiene la información acerca del iva de la factura.
     * @return <type>
     */
    public function getIva() {
        return $this->iva;
    }

    /**
     * Contiene la información acerca del tipo de venta (credito o contado)
     * @var <type> 
     */
    private $tipo_venta = null;

    /**
     * Establece la información acerca del tipo de venta
     * @param <type> $param
     */
    public function setTipoVenta($_tipo_venta) {
        $this->tipo_venta = $_tipo_venta;
    }

    /**
     * Obtiene la información acerca del tipo de venta
     * @return <type>
     */
    public function getTipoVenta() {
        return $this->tipo_venta;
    }

    /**
     * Verifica que el objeto contenga toda la informacion necesaria.
     * @var Object
     */
    private $success = null;
    /**
     * Contiene información acerca de posibles errores
     * @var String
     */
    private $error = "";

    /**
     * Obtiene información acerca de posibles errores
     * @return <type>
     */
    public function getError() {
        return $this->error;
    }

    /**
     * Establece información acerca de posibles errores
     * @param <type> $param
     */
    public function setError($param) {
        $this->error = $param;
    }
    
    /**
     * Contiene información acerca del numero de cuenta del cheque o targeta con la que se realizo el pago
     * @var String
     */
    private $numero_cuenta = "";

    /**
     * Obtiene información acerca del  numero de cuenta del cheque o targeta con la que se realizo el pago
     * @return <type>
     */
    public function getNumeroCuenta() {
        return $this->numero_cuenta;
    }

    /**
     * Establece información acerca del  numero de cuenta del cheque o targeta con la que se realizo el pago
     * @param <type> $param
     */
    public function setNumeroCuenta($param) {
        $this->numero_cuenta = $param;
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

        //verificamos si existe el 
        if (!( $this->getFecha() != null && $this->getFecha() != "")) {
            $this->setError("No se ha definido la fecha.");
        }

        //verificamos si existe el
        if (!($this->getFolioInterno() != null && $this->getFolioInterno() != "")) {
            $this->setError("No se ha definido el folio interno.");
        }

        //verificamos si existe el
        if (!($this->getFormaDePago() != null && $this->getFormaDePago() != "")) {
            $this->setError("No se ha definido la forma de pago.");
        }

        //verificamos si existe el
        if (!($this->getIva() != null && $this->getIva() != "")) {
            $this->setError("No se ha definido el iva.");
        }

        //verificamos si existe el metodo de pago
        if (!($this->getMetodoDePago() != null && $this->getMetodoDePago() != "")) {
            $this->setError("No se ha definido el metodo de pago.");
        }


        //verificamos si existe el
        if (!($this->getSerie() != null && $this->getSerie() != "")) {
            $this->setError("No se ha definido la serie.");
        }

        //verificamos si existe el
        if (!($this->getSubtotal() != null && $this->getSubtotal() != "")) {
            $this->setError("No se ha definido el subtotal.");
        }

        //verificamos si existe el
        if (!($this->getTotal() != null && $this->getTotal() != "")) {
            $this->setError("No se ha definido el total.");
        }
                               
        //verificamos si se especifico el numero de cuenta en caso de realizar el pago con cheque
        if (!($this->getMetodoDePago() != null &&  $this->getMetodoDePago() != "" ) &&  $this->getMetodoDePago() == "cheque" ) {                                
            
            if( $this->getNumeroCuenta() == "" || $this->getNumeroCuenta() == null ){
                $this->setError("No se ha definido el numero de cuenta.");
            }
            
        }
                
        $this->success = new Success($this->error);
        return $this->success;
    }

}

?>