<?php
/**
  * GET api/precio/servicio/editar_precio_rol
  * Edita la relacion de precio de uno o varios servicios con un rol
  *
  * Edita la relacion de precio de uno o varios servicios con un rol
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.editar_precio_rol.php");

$api = new ApiPrecioServicioEditar_precio_rol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
