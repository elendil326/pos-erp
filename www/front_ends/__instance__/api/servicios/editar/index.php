<?php
/**
  * GET api/servicios/editar
  * Edita un servicio
  *
  * Edita un servicio
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.servicios.editar.php");

$api = new ApiServiciosEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
