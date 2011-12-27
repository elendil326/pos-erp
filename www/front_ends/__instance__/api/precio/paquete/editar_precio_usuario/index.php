<?php
/**
  * GET api/precio/paquete/editar_precio_usuario
  * Edita la relacion de precio con uno o varios paquetes para un usuario
  *
  * Edita la relacion de precio con uno o varios paquetes para un usuario
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.editar_precio_usuario.php");

$api = new ApiPrecioPaqueteEditarPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
