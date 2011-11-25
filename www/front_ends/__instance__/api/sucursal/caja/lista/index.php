<?php
/**
  * GET api/sucursal/caja/lista
  * Lista las cajas
  *
  * Lista las cajas. Se puede filtrar por la sucursal a la que pertenecen.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.lista.php");

$api = new ApiSucursalCajaLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
