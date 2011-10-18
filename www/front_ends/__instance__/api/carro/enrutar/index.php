<?php
/**
  * GET api/carro/enrutar
  * Enviar un cargamento
  *
  * Enviar un cargamento. No necesariamente debe tener cargamento. Seria excelente calcular el kilometraje. La sucursal origen sera tomada de la sesion actual.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.enrutar.php");

$api = new ApiCarroEnrutar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
