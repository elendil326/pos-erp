<?php
/**
  * GET api/servicios/clasificacion/editar
  * Edita una clasificacion de servicio
  *
  * Edita la informaci?n de una clasificaci?n de servicio
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.clasificacion.editar.php");

$api = new ApiServiciosClasificacionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
