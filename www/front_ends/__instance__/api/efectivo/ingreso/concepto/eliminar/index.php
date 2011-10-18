<?php
/**
  * GET api/efectivo/ingreso/concepto/eliminar
  * Deshabilita un concepto de ingreso
  *
  * Deshabilita un concepto de ingreso

Update :Se deber?tambi?obtener de la sesi?l id del usuario y fecha de la ultima modificaci?
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.concepto.eliminar.php");

$api = new ApiEfectivoIngresoConceptoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
