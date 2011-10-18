<?php
/**
  * GET api/carro/nuevo
  * Crea un nuevo carro
  *
  * Crea un nuevo carro. La fecha de creacion sera tomada del servidor.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.nuevo.php");

$api = new ApiCarroNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
