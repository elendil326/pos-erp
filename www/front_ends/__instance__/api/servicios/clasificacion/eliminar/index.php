<?php
/**
  * GET api/servicios/clasificacion/eliminar
  * Elimina una clasificacion de servicio
  *
  * Elimina una clasificacion de servicio
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.clasificacion.eliminar.php");

$api = new ApiServiciosClasificacionEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
