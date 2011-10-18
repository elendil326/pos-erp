<?php
/**
  * GET api/servicios/orden/cancelar
  * Cancela una orden de servicio
  *
  * Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.cancelar.php");

$api = new ApiServiciosOrdenCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
