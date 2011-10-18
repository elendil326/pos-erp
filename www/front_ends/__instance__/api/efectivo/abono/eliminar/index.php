<?php
/**
  * GET api/efectivo/abono/eliminar
  * Elimina un abono
  *
  * Cancela un abono
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.abono.eliminar.php");

$api = new ApiEfectivoAbonoEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
