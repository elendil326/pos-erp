<?php
/**
  * POST api/servicios/orden/terminar
  * Dar por terminada una orden
  *
  * Dar por terminada una orden, cuando el cliente satisface el ultimo pago
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.terminar.php");

$api = new ApiServiciosOrdenTerminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
