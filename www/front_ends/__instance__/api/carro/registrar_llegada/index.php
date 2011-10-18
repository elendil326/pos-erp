<?php
/**
  * GET api/carro/registrar_llegada
  * Registra llegada del carro
  *
  * Registra la llegada de un carro a una sucursal. La fecha sera tomada del servidor
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.registrar_llegada.php");

$api = new ApiCarroRegistrar_llegada();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
