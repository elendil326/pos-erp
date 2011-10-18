<?php
/**
  * GET api/precio/servicio/editar_precio_tipo_cliente
  * Edita la relacion de precio con uno o varios servicios para un tipo de cliente
  *
  * Edita la relacion de precio con uno o varios servicios para un tipo de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.editar_precio_tipo_cliente.php");

$api = new ApiPrecioServicioEditar_precio_tipo_cliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
