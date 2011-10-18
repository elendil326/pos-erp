<?php
/**
  * GET api/servicos/orden/terminar 
  * Dar por terminada una orden
  *
  * Dar por terminada una orden, cuando el cliente satisface el ultimo pago
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicos.orden.terminar .php");

$api = new ApiServicosOrdenTerminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
