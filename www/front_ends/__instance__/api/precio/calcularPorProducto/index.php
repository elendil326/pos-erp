<?php
/**
  * GET api/precio/calcularPorProducto
  * Calcula el precio de un producto
  *
  * Calcula el precio de un producto. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.precio.calcularPorProducto.php");

$api = new ApiPrecioCalcularPorProducto();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
