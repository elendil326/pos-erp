<?php
/**
  * GET api/consignacion/nueva
  * Iniciar una orden de consignaci?
  *
  * Iniciar una orden de consignaci?La fecha sera tomada del servidor.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.nueva.php");

$api = new ApiConsignacionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
