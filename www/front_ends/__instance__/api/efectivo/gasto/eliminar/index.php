<?php
/**
  * GET api/efectivo/gasto/eliminar
  * Elimina un gasto
  *
  * Cancela un gasto 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.eliminar.php");

$api = new ApiEfectivoGastoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
