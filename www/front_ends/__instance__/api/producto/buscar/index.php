<?php
/**
  * GET api/producto/buscar
  * Buscar productos
  *
  * Buscar productos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.producto.buscar.php");

$api = new ApiProductoBuscar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
