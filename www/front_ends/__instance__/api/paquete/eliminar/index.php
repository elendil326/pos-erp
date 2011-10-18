<?php
/**
  * GET api/paquete/eliminar
  * Elimina un paquete
  *
  * Desactiva un paquete.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.eliminar.php");

$api = new ApiPaqueteEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
