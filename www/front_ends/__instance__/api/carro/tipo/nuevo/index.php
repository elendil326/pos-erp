<?php
/**
  * GET api/carro/tipo/nuevo
  * Agrega un nuevo tipo de carro
  *
  * Agrega un nuevo tipo de carro ( camion, camioneta, etc)
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.tipo.nuevo.php");

$api = new ApiCarroTipoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
