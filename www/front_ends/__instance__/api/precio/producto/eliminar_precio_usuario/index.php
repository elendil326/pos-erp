<?php
/**
  * GET api/precio/producto/eliminar_precio_usuario
  * Elimina la relacion del precio de un producto con un usuario
  *
  * Elimina la relacion del precio de un producto con un usuario
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.eliminar_precio_usuario.php");

$api = new ApiPrecioProductoEliminar_precio_usuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
