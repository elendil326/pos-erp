<?php
/**
  * GET api/precio/tarifa/lista
  * Lista las tarifas existentes
  *
  * Lista las tarifas existentes. Se puede ordenar de acuerdo a los atributos de la tabla y se puede filtrar por el tipo de tarifa, la moneda que usa y por el valor de activa.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.lista.php");

$api = new ApiPrecioTarifaLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
