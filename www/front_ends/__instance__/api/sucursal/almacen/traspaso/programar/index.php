<?php
/**
  * GET api/sucursal/almacen/traspaso/programar
  * Crea un registro de traspaso
  *
  * Crea un registro de traspaso de producto de un almacen a otro. El usuario que envia sera tomada de la sesion.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.programar.php");

$api = new ApiSucursalAlmacenTraspasoProgramar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
