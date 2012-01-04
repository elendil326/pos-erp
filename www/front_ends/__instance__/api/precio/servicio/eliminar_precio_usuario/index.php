<?php
/**
  * GET api/precio/servicio/eliminar_precio_usuario
  * Elimina la relacion del precio de un servicio con un usuario
  *
  * Elimina la relacion del precio de un servicio con un usuario
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.eliminar_precio_usuario.php");

$api = new ApiPrecioServicioEliminarPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
