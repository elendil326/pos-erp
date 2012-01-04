<?php
/**
  * GET api/efectivo/ingreso/concepto/eliminar
  * Deshabilita un concepto de ingreso
  *
  * Deshabilita un concepto de ingreso

Update :Se deber?a tambi?n obtener de la sesi?n el id del usuario y fecha de la ultima modificaci?n.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.concepto.eliminar.php");

$api = new ApiEfectivoIngresoConceptoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
