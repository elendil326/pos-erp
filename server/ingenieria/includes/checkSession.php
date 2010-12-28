<?php


require_once('../../server/config.php');
require_once('logger.php');
require_once('controller/login.controller.php');

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

