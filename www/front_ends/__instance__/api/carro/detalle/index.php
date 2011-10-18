<?php
/**
  * GET api/carro/detalle
  * Ver los detalles e historial de un carro especifico
  *
  * Ver los detalles e historial de un carro especifico
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.detalle.php");

$api = new ApiCarroDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
