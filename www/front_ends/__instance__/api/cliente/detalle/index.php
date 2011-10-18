<?php
/**
  * GET api/cliente/detalle
  * Obtener los detalles de un cliente.
  *
  * Obtener los detalles de un cliente.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.cliente.detalle.php");

$api = new ApiClienteDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
