<?php
/**
  * GET api/ventas/detalle
  * Lista el detalle de una venta
  *
  * Lista el detalle de una venta, se puede ordenar de acuerdo a sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.ventas.detalle.php");

$api = new ApiVentasDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
