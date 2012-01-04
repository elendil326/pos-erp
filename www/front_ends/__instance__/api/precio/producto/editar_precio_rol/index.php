<?php
/**
  * GET api/precio/producto/editar_precio_rol
  * Edita la relacion de precio de uno o varios productos con un rol
  *
  * Edita la relacion de precio de uno o varios productos con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.editar_precio_rol.php");

$api = new ApiPrecioProductoEditarPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
