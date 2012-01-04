<?php
/**
  * GET api/sucursal/lista/caja
  * Lista las cajas
  *
  * Lista las cajas. Se puede filtrar por la sucursal a la que pertenecen.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.lista.caja.php");

$api = new ApiSucursalListaCaja();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
