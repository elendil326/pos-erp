<?php


require_once('../../server/config.php');
require_once('logger.php');
require_once('controller/login.controller.php');


if(!checkCurrentSession()){
    Logger::log("Sesion invalida para admin.");
    logOut(false);
  	die('<script>window.location = "log.php"</script>');
}


if($_SESSION['grupo'] != 1){
    Logger::log("agluien con grupo menor intento ingresar a admin");
    logOut(false);
  	die('<script>window.location = "log.php"</script>');
}






