<?php
/**
  * GET api/precio/producto/nuevo_precio_tipo_cliente
  * Asigna un precio a un producto para determinado tipo de cliente
  *
  * Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo relaciona un precio a uno o varios productos con un tipo de cliente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.nuevo_precio_tipo_cliente.php");

$api = new ApiPrecioProductoNuevoPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
