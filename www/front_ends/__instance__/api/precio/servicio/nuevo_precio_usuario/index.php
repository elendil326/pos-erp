<?php
/**
  * GET api/precio/servicio/nuevo_precio_usuario
  * Relaciona un usuario con servicios a un precio o utilidad 
  *
  * Relaciona un usuario con servicios a un precio o utilidad 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.servicio.nuevo_precio_usuario.php");

$api = new ApiPrecioServicioNuevo_precio_usuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
