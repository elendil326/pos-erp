<?php
/**
  * GET api/precio/servicio/editar_precio_usuario
  * Edita la relacion de precio con uno o varios servicios para un usuario
  *
  * Edita la relacion de precio con uno o varios servicios para un usuario
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.editar_precio_usuario.php");

$api = new ApiPrecioServicioEditar_precio_usuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
