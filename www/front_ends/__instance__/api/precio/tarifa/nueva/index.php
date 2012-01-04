<?php
/**
  * POST api/precio/tarifa/nueva
  * Crea una nueva tarifa
  *
  * Crea una nueva tarifa 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.nueva.php");

$api = new ApiPrecioTarifaNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
