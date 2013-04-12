<?php
      require_once("../../../server/bootstrap.php");
      session_start();//Permite cargar las variables de session
       
       $Archivo = file_get_contents(POS_PATH_TO_SERVER_ROOT."/../static_content/" . IID . "/temp/" . $_SESSION["ArchivoDescarga"]);
      
      foreach($_SESSION["CabsDescarga"] as $nCab)
      {
            header($nCab);//Agrega las cabeceras a la página
      }
      echo readfile($Archivo);//Muestra el archivo por la ventana de salida
?>