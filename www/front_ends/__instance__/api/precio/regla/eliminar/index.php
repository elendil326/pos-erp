<?php
/**
  * POST api/precio/regla/eliminar
  * Elimina una regla
  *
  * Elimina una regla. La regla por default de l aversion por default de la tarifa por default no puede ser eliminada
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.regla.eliminar.php");

$api = new ApiPrecioReglaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
