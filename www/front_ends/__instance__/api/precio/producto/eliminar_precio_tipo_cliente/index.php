<?php
/**
  * GET api/precio/producto/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un producto con un tipo de cliente
  *
  * Elimina la relacion del precio de un producto con un tipo de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.eliminar_precio_tipo_cliente.php");

$api = new ApiPrecioProductoEliminarPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
