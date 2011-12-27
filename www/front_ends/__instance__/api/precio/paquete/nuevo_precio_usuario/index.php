<?php
/**
  * GET api/precio/paquete/nuevo_precio_usuario
  * Relaciona un usuario con paquetes a un precio o utilidad 
  *
  * Relaciona un usuario con paquetes a un precio o utilidad 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.nuevo_precio_usuario.php");

$api = new ApiPrecioPaqueteNuevoPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
