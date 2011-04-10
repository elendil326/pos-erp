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
     * Crea un objeto que contiene toda la informacion de todos los productos
     * involucrados en esa venta y verifica que sean validos.
     *
     * @param Array $items
     */
    public function __construct($items){


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
     * @var String
     */
    private $id_producto = null;

    /**
     *
     * @return <type>
     */
    public function getIdProducto(){

        return $this->id_producto;

    }

    /**
     *
     * @param <type> $param
     */
    public function setIdProducto($param){
        $this->id_producto = $param;
    }

    /**
     *
     * @var String
     */
    private $cantidad = null;

    /**
     *
     * @return <type>
     */
    public function getCantidad(){

        return $this->cantidad;

    }

    /**
     *
     * @param <type> $param
     */
    public function setCantidad($param){
        $this->cantidad = $param;
    }

    /**
     *
     * @var String
     */
    private $unidad = null;

    /**
     *
     * @return <type>
     */
    public function getUnidad(){

        return $this->unidad;

    }

    /**
     *
     * @param <type> $param
     */
    public function setUnidad($param){
        $this->unidad = $param;
    }

    /**
     *
     * @var String
     */
    private $descripcion = null;

    /**
     *
     * @return <type>
     */
    public function getDescripcion(){

        return $this->descripcion;

    }

    /**
     *
     * @param <type> $param
     */
    public function setDescripcion($param){
        $this->descripcion = $param;
    }

    /**
     *
     * @var String
     */
    private $valor = null;
    /**
     *
     * @return <type>
     */
    public function getValor(){

        return $this->valor;

    }

    /**
     *
     * @param <type> $param
     */
    public function setValor($param){
        $this->valor = $param;
    }


    /**
     *
     * @var String
     */
    private $importe = null;
    /**
     *
     * @return <type>
     */
    public function getImporte(){

        return $this->importe;

    }

    /**
     *
     * @param <type> $param
     */
    public function setImporte($param){
        $this->importe = $param;
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