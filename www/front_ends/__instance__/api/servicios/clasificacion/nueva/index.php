<?php
/**
  * GET api/servicios/clasificacion/nueva
  * Genera una nueva clasificacion de servicio
  *
  * Genera una nueva clasificacion de servicio
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.clasificacion.nueva.php");

$api = new ApiServiciosClasificacionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
