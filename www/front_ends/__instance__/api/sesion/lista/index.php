<?php
/**
  * GET api/sesion/lista
  * Obtener las sesiones activas.
  *
  * Obtener las sesiones activas.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sesion.lista.php");

$api = new ApiSesionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
