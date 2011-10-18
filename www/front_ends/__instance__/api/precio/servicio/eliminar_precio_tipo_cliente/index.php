<?php
/**
  * GET api/precio/servicio/eliminar_precio_tipo_cliente
  * Elimina la relacion del precio de un servicio con un tipo de cliente
  *
  * Elimina la relacion del precio de un servicio con un tipo de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.eliminar_precio_tipo_cliente.php");

$api = new ApiPrecioServicioEliminar_precio_tipo_cliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
