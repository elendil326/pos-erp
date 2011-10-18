<?php
/**
  * GET api/carro/marca/nuevo
  * Agrega una nueva marca de carro
  *
  * Agrega una nueva marca de carro
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.marca.nuevo.php");

$api = new ApiCarroMarcaNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
