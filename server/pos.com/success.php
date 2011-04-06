<?php

/**
 * Description of success
 *
 * @author Manuel
 */
class Success {
    
    /**
     * Contiene informacion acerca del success
     * @var String 
     */
    private $info = "Error : Informacion sin definir";

    /**
     * Obtiene informacion acerca del success
     * @return String
     */
    public function getInfo(){
        return $this->info;
    }

    /**
     * Establece informacion acerca del success
     * @param String $param
     */
    private function setInfo($param){
        $this->info = $param;
    }

    /**
     * Estado del success
     * @var Boolean
     */
    private $success = false;

    /**
     * Obtiene el estado del success
     * @return Boolean
     */
    public function getSuccess(){
        return $this->success;
    }


    /**
     * Establece el estado del success
     * @param Boolean $param
     */
    private function setSuccess($param){
        $this->success = $param;
    }        


    /**
     *
     */
    public function __construct($error = null){

        if (isset($error) && $error == "") {
            $this->setSuccess(true);
            $this->setInfo("Operación realizada con éxito");
        }

        if (isset($error) && $error != ""){
            $this->setSuccess(false);
            $this->setInfo($error);
        }

        return $this;

    }

}

?>
