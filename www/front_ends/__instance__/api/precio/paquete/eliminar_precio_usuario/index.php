<?php
/**
  * GET api/precio/paquete/eliminar_precio_usuario
  * Elimina la relacion del precio de un paquete con un usuario
  *
  * Elimina la relacion del precio de un paquete con un usuario
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.eliminar_precio_usuario.php");

$api = new ApiPrecioPaqueteEliminarPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
