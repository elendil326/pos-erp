<?php
/**
  * GET api/paquete/editar
  * Edita la informacion de un paquete
  *
  * Edita la informacion de un paquete
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.editar.php");

$api = new ApiPaqueteEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
