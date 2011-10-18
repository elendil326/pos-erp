<?php
/**
  * GET api/carro/modelo/nuevo
  * Crea un nuevo modelo de carro
  *
  * Crea un nuevo modelo de carro
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.modelo.nuevo.php");

$api = new ApiCarroModeloNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
