<?php
/**
  * GET api/precio/producto/editar_precio_tipo_cliente
  * Edita la relacion de precio de uno o varios productos con un tipo de cliente
  *
  * Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo edita la relacion de un precio a uno o varios productos con un tipo de cliente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.editar_precio_tipo_cliente.php");

$api = new ApiPrecioProductoEditar_precio_tipo_cliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
