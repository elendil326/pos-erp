<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");
	
/**
 * Archivo que contiene la clase Generales la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Generales{

    /**
     *
     * @var String
     */
    private $serie = null;

    /**
     *
     * @param <type> $param 
     */
    public function setSerie($param){
        $this->serie = $param;
    }

    /**
     *
     * @return <type> 
     */
    public function getSerie(){
        return $this->serie;
    }

    /**
     *
     * @var <type>
     */
    private $folio_interno = null;

    /**
     *
     * @param <type> $param
     */
    public function setFolioInterno($param){
        $this->folio_interno = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getFolioInterno(){
        return $this->folio_interno;
    }

    /**
     *
     * @var <type>
     */
    private $fecha = null;
    /**
     *
     * @param <type> $param
     */
    public function setFecha($param){
        $this->fecha = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getFecha(){
        return $this->fecha;
    }


    /**
     *
     * @var <type>
     */
    private $forma_de_pago = null;

    /**
     *
     * @param <type> $param
     */
    public function setFormaDePago($param){
        $this->forma_de_pago = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getFormaDePago(){
        return $this->forma_de_pago;
    }

    /**
     *
     * @var <type>
     */
    private $metodo_de_pago = null;
    /**
     *
     * @param <type> $param
     */
    public function setMetodoDePago($param){
        $this->metodo_de_pago = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getMetodoDePago(){
        return $this->metodo_de_pago;
    }


    /**
     *
     * @var <type>
     */
    private $subtotal = null;
    /**
     *
     * @param <type> $param
     */
    public function setSubtotal($param){
        $this->subtotal = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getSubtotal(){
        return $this->subtotal;
    }


    /**
     *
     * @var <type>
     */
    private $total = null;

    /**
     *
     * @param <type> $param
     */
    public function setTotal($param){
        $this->total = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getTotal(){
        return $this->total;
    }

    /**
     *
     * @var <type> 
     */
    private $iva = null;
    /**
     *
     * @param <type> $param
     */
    public function setIva($param){
        $this->iva = $param;
    }

    /**
     *
     * @return <type>
     */
    public function getIva(){
        return $this->iva;
    }

    /**
     * Contiene informacion acerca de la crecion de da
     * @var Object
     */
    private $success = null;

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