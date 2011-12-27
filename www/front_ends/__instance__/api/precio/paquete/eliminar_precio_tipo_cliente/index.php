<?php
/**
  * GET api/precio/paquete/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un paquete con un tipo de cliente
  *
  * Elimina la relacion del precio de un paquete con un tipo de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.eliminar_precio_tipo_cliente.php");

$api = new ApiPrecioPaqueteEliminarPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
