<?php

require_once("success.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/server/logger.php");

/**
 * Archivo que contiene la clase Comceptos la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Conceptos {

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Conceptos";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Array que contiene objetos de tipo Concepto
     * @var array
     */
    private $conceptos = array();

    /**
     * Agrega un nuevo concepto al arreglo de conceptos
     * @param <Concepto> $concepto
     */
    public function addConcepto($concepto) {
        array_push($this->conceptos, $concepto);
    }

    /**
     * Obtiene todos los conceptos
     * @return <Conceptos>
     */
    public function getConceptos() {
        return $this->conceptos;
    }

    /**
     * Contiene la información acerca de posibles errores.
     * @var String
     */
    private $error = "";

    /**
     * Obtiene la información acerca de posibles errores.
     * @return <type>
     */
    public function getError() {

        return $this->error;
    }
    /**
     * Establece la información acerca de posibles errores.
     * @param <type> $param
     */
    public function setError($param) {
        $this->error = $param;
    }
    /**
     * Crea un objeto que contiene toda la informacion de todos los productos
     * involucrados en esa venta y verifica que sean validos.
     *
     * @param Array $items
     */
    public function __construct() {

    }
    /**
     * Verifica que el objeto contenga toda la informacion necesaria
     * @return boolean
     */
    public function isValid() {

        foreach ($this->conceptos as $concepto) {
            $success = $concepto->isValid();

            if (!$success->getSuccess()) {
                $this->setError($success->getInfo());
                break;
            }
        }

        $this->success = new Success($this->error);
        return $this->success;
    }

}

class Concepto {

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Concepto";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @var String
     */
    private $id_producto = null;

    /**
     *
     * @return <type>
     */
    public function getIdProducto() {

        return $this->id_producto;
    }

    /**
     *
     * @param <type> $param
     */
    public function setIdProducto($param) {
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
    public function getCantidad() {

        return $this->cantidad;
    }

    /**
     *
     * @param <type> $param
     */
    public function setCantidad($param) {
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
    public function getUnidad() {

        return $this->unidad;
    }

    /**
     *
     * @param <type> $param
     */
    public function setUnidad($param) {
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
    public function getDescripcion() {

        return $this->descripcion;
    }

    /**
     *
     * @param <type> $param
     */
    public function setDescripcion($param) {
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
    public function getValor() {

        return $this->valor;
    }

    /**
     *
     * @param <type> $param
     */
    public function setValor($param) {
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
    public function getImporte() {

        return $this->importe;
    }

    /**
     *
     * @param <type> $param
     */
    public function setImporte($param) {
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

        //verificamos si existe el
        if (!(isset($this->getDescripcion()) && $this->getDescripcion() = !"")) {
            $this->setError("No se ha definido la descripción.");
        }

        //verificamos si existe el
        if (!(isset($this->getCantidad()) && $this->getCantidad() = !"")) {
            $this->setError("No se ha definido la cantidad del producto : {$this->getDescripcion()}.");
        }

        //verificamos si existe el
        if (!(isset($this->getIdProducto()) && $this->getIdProducto() = !"")) {
            $this->setError("No se ha definido el id del producto : {$this->getDescripcion()}.");
        }

        //verificamos si existe el
        if (!(isset($this->getImporte()) && $this->getImporte() = !"")) {
            $this->setError("No se ha definido el importe del producto : {$this->getDescripcion()}.");
        }

        //verificamos si existe el
        if (!(isset($this->getUnidad()) && $this->getUnidad() = !"")) {
            $this->setError("No se ha definido la unidad del producto : {$this->getDescripcion()}.");
        }

        //verificamos si existe el
        if (!(isset($this->getValor()) && $this->getValor() = !"")) {
            $this->setError("No se ha definido el valor del producto : {$this->getDescripcion()}.");
        }

        $this->success = new Success($this->getError());
        return $this->success;
    }

}

?>