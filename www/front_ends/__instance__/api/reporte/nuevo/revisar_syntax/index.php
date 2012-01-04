<?php
/**
  * GET api/reporte/nuevo/revisar_syntax
  * Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
  *
  * Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.reporte.nuevo.revisar_syntax.php");

$api = new ApiReporteNuevoRevisarSyntax();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
