<?php
/**
  * GET api/ventas/cancelar
  * Cancelar una venta
  *
  * Metodo que cancela una venta
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.ventas.cancelar.php");

$api = new ApiVentasCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
