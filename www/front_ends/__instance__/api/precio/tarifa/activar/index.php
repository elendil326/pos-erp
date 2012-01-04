<?php
/**
  * POST api/precio/tarifa/activar
  * Activa una tarifa previamente eliminada
  *
  * Activa una tarifa preciamente eliminada
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.activar.php");

$api = new ApiPrecioTarifaActivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
