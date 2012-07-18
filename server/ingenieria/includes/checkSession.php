<?php


require_once('../../server/config.php');

require_once('controller/login.controller.php');

if(isset($_REQUEST['action'])){
	Logger::log("Ingeniero ha solicitado " . $_SERVER["PHP_SELF"] . " modulo " . $_REQUEST['action']);	
}else{
	Logger::log("Ingeniero ha solicitado " . $_SERVER["PHP_SELF"] .  " sin modulo !");	
}


if(!checkCurrentSession()){
    Logger::log("Sesion invalida para ingenieria");
    logOut(false);
  	die('<script>window.location = "../admin/log.php"</script>');
}


if($_SESSION['grupo'] > 0){
    Logger::log("Acceso no autorizado para seccion de igenieria");
    logOut(false);
  	die('<script>window.location = "../admin/log.php"</script>');
}

