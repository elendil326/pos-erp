<?php
/**
  * GET api/carro/modelo/editar
  * Editar el modelo del carro
  *
  * Editar el modelo del carro
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.carro.modelo.editar.php");

$api = new ApiCarroModeloEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
