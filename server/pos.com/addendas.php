<?php

require_once("success.php");

/**
 * Archivo que contiene la clase Impuestos la cual provee de los medios necesarios para validar
 * la estructura de los datos generales del formato de solicitud de factura electronica
 */
class Addendas{

    /**
     * Nombre de la clase
     * @var String Nombre de la clase
     */
    private $type = "Addendas";

    /**
     * Regresa el nombre de esta clase
     * @return String Nombde de la clase
     */
    public function getType(){
        return $this->type;
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

        $this->success = new Success($this->getError());
        return $this->success;
        
    }
	
}

?>