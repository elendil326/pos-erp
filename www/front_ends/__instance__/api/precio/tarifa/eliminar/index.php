<?php
/**
  * POST api/precio/tarifa/eliminar
  * Desactiva una tarifa
  *
  * Desactiva una tarifa. Para poder desactivar una tarifa, esta no tiene que estar asignada como default para ningun usuario. La tarifa default del sistema no puede ser eliminada.

La tarifa instalada por default no puede ser eliminada
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.eliminar.php");

$api = new ApiPrecioTarifaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
