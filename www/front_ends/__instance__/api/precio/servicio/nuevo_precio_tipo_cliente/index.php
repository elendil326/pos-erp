<?php
/**
  * GET api/precio/servicio/nuevo_precio_tipo_cliente
  * Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
  *
  * Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.nuevo_precio_tipo_cliente.php");

$api = new ApiPrecioServicioNuevoPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
