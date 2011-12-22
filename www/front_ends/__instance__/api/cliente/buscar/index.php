<?php
/**
  * GET api/cliente/buscar
  * Buscar un cliente por su nombre u otros datos
  *
  * Busca una lista de clientes dada una busqueda
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.cliente.buscar.php");

$api = new ApiClienteBuscar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
