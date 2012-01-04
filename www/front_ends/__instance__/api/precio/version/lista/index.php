<?php
/**
  * GET api/precio/version/lista
  * Lista las versiones existentes
  *
  * Lista las versiones existentes, se puede filtrar por la tarifa y ordenar por los atributos de al tabla
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.lista.php");

$api = new ApiPrecioVersionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
