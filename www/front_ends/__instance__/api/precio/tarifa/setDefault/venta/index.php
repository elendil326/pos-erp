<?php
/**
  * POST api/precio/tarifa/setDefault/venta
  * Selecciona como default para las ventas una tarifa de venta
  *
  * Selecciona como default para las ventas una tarifa de venta. Esta tarifa sera usada para todas las ventas a menos que el usuario indique otra tarifa de venta.

Solo puede asignarse como default de ventas una tarifa de tipo venta
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.setDefault.venta.php");

$api = new ApiPrecioTarifaSetDefaultVenta();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
