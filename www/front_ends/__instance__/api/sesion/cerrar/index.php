<?php
/**
  * POST api/sesion/cerrar
  * Cerrar la sesion actual.
  *
  * Regresa un url de redireccion seg?n el tipo de usuario.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sesion.cerrar.php");

$api = new ApiSesionCerrar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
