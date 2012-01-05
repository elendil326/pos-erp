<?php
/**
  * POST api/precio/tarifa/eliminar
  * Desactiva una tarifa
  *
  * Desactiva una tarifa. una tarifa no puede ser eliminada si es la default del sistema o si esta como default para algun usuario,rol,clasificacion de cliente o proveedor.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.eliminar.php");

$api = new ApiPrecioTarifaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
