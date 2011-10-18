<?php
/**
  * GET api/carro/marca/editar
  * Edita una marca de un carro
  *
  * Edita una marca de un carro
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.marca.editar.php");

$api = new ApiCarroMarcaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
