<?php
/**
  * GET api/precio/servicio/eliminar_precio_rol
  * Elimina la relacion del precio de un servicio con un rol
  *
  * Elimina la relacion del precio de un servicio con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.eliminar_precio_rol.php");

$api = new ApiPrecioServicioEliminarPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
