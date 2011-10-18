<?php
/**
  * GET api/reporte/detalle
  * Obtener un detalle de un reporte
  *
  * Obtener un detalle de un reporte
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.reporte.detalle.php");

$api = new ApiReporteDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
