<?php
/**
  * GET api/efectivo/gasto/concepto/eliminar
  * Deshabilita un concepto de gasto
  *
  * Deshabilita un concepto de gasto
Update :Se deber?a de tomar tambi?n de la sesi?n el id del usuario y fecha de la ultima modificaci?n
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.eliminar.php");

$api = new ApiEfectivoGastoConceptoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
