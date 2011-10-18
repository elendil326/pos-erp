<?php
/**
  * GET api/sucursal/caja/abrir
  * Abrir una caja, esta caja se asociara a esta sesion.
  *
  * Valida si una maquina que realizara peticiones al servidor pertenece a una sucursal.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.abrir.php");

$api = new ApiSucursalCajaAbrir();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
