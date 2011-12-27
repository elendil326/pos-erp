<?php
/**
  * GET api/precio/paquete/nuevo_precio_tipo_cliente
  * Relaciona un tipo de cliente con paquetes a un precio o utilidad determinados
  *
  * Relaciona un tipo de cliente con paquetes a un precio o utilidad determinados
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.nuevo_precio_tipo_cliente.php");

$api = new ApiPrecioPaqueteNuevoPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
