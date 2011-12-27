<?php
/**
  * GET api/precio/paquete/nuevo_precio_rol
  * Relaciona un rol con productos al precio o utilidad que se le seran vendidos
  *
  * Relaciona un rol con paquetess al precio o utilidad que se le seran vendidos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.paquete.nuevo_precio_rol.php");

$api = new ApiPrecioPaqueteNuevoPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
