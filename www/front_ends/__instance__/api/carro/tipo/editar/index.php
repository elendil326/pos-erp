<?php
/**
  * GET api/carro/tipo/editar
  * Edita un tipo de carro
  *
  * Edita un registro de tipo de carro (camion, camioneta, etc)
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.tipo.editar.php");

$api = new ApiCarroTipoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
