<?php
/**
  * GET api/precio/paquete/editar_precio_rol
  * Edita la relacion de precio de uno o varios paquetes con un rol
  *
  * Edita la relacion de precio de uno o varios paquetes con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.editar_precio_rol.php");

$api = new ApiPrecioPaqueteEditarPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
