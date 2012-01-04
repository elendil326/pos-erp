<?php
/**
  * GET api/consignacion/nueva
  * Iniciar una orden de consignaci?n
  *
  * Iniciar una orden de consignaci?n. La fecha sera tomada del servidor.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.nueva.php");

$api = new ApiConsignacionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
