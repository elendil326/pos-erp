<?php
/**
  * GET api/carro/transbordo
  * Mover mercancia de un carro a otro.
  *
  * Mover mercancia de un carro a otro. 
UPDATE
Se movera parcial o totalmente la carga?
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.transbordo.php");

$api = new ApiCarroTransbordo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
