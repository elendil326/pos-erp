<?php
/**
  * GET api/reporte/nuevo
  * genera un reporte a la medida
  *
  * Un usuario con grupo 1 o con el permiso puede generar reportes a la medida.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.reporte.nuevo.php");

$api = new ApiReporteNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
