<?php
/**
  * GET api/efectivo/ingreso/eliminar
  * Elimina un ingreso
  *
  * Cancela un ingreso
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.eliminar.php");

$api = new ApiEfectivoIngresoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
