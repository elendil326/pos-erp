<?php
/**
  * GET api/reporte/lista
  * Lista todos los reportes
  *
  * Lista todos los reportes, pueden filtrarse por empresa, por sucursal, y ordenarse por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.reporte.lista.php");

$api = new ApiReporteLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
