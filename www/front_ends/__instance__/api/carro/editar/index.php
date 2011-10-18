<?php
/**
  * GET api/carro/editar
  * Edita un carro
  *
  * Edita la informacion de un carro
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.editar.php");

$api = new ApiCarroEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
