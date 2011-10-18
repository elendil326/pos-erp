<?php
/**
  * GET api/efectivo/gasto/concepto/eliminar
  * Deshabilita un concepto de gasto
  *
  * Deshabilita un concepto de gasto
Update :Se deber?de tomar tambi?de la sesi?l id del usuario y fecha de la ultima modificaci?
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.eliminar.php");

$api = new ApiEfectivoGastoConceptoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
