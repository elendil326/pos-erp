<?php
/**
  * POST api/precio/tarifa/setDefault/compra
  * Asigna el default a una tarifa de compra
  *
  * Asigna el default a una tarifa de compra. La tarifa default es la que se usara en todas las compras a menos que el usuario indique otra tarifa.

Solo se puede elegir una tarifa de tipo compra.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.setDefault.compra.php");

$api = new ApiPrecioTarifaSetDefaultCompra();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
