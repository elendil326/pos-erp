<?php
/**
  * POST api/precio/version/eliminar
  * Elimina una version 
  *
  * Elimina una version permamentemente de la base de datos junto a todas sus reglas.

La version default de la tarifa no puede ser eliminada ni la version activa.

La version por default de la tarifa instalada por default no puede ser eliminada
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.eliminar.php");

$api = new ApiPrecioVersionEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
