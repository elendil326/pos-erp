<?php
/**
  * GET api/precio/producto/eliminar_precio_rol
  * Elimina la relacion del precio de un producto con un rol
  *
  * Elimina la relacion del precio de un producto con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.eliminar_precio_rol.php");

$api = new ApiPrecioProductoEliminarPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
