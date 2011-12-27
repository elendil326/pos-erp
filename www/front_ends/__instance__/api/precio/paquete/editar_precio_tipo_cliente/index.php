<?php
/**
  * GET api/precio/paquete/editar_precio_tipo_cliente
  * Edita la relacion de precio con uno o varios paquetes para un tipo de cliente
  *
  * Edita la relacion de precio con uno o varios paquetes para un tipo de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.editar_precio_tipo_cliente.php");

$api = new ApiPrecioPaqueteEditarPrecioTipoCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
