<?php
/**
  * GET api/sucursal/nueva
  * Crea una nueva sucursal
  *
  * Metodo que crea una nueva sucursal
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.nueva.php");

$api = new ApiSucursalNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
