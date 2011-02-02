<?php


require_once('../../server/config.php');
require_once('controller/login.controller.php');

if(isset($_REQUEST['action'])){
	Logger::log("Admin ha solicitado " . $_SERVER["PHP_SELF"] . " modulo " . $_REQUEST['action']);	
}else{
	Logger::log("Admin ha solicitado " . $_SERVER["PHP_SELF"] .  " sin modulo !");	
}


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






