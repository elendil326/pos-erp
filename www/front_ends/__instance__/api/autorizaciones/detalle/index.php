<?php
/**
  * GET api/autorizaciones/detalle
  * Muestra la informacion detallada de una autorizacion.
  *
  * Muestra la informacion detallada de una autorizacion.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.detalle.php");

$api = new ApiAutorizacionesDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
