<?php
/**
  * GET api/efectivo/moneda/eliminar
  * Elimina una moneda
  *
  * Desactiva una moneda
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.moneda.eliminar.php");

$api = new ApiEfectivoMonedaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
