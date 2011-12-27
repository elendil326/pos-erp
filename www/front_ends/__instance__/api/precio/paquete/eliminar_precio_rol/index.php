<?php
/**
  * GET api/precio/paquete/eliminar_precio_rol
  * Elimina la relacion del precio de un paquete con un rol
  *
  * Elimina la relacion del precio de un paquete con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.eliminar_precio_rol.php");

$api = new ApiPrecioPaqueteEliminarPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
