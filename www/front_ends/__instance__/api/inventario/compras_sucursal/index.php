<?php
/**
  * GET api/inventario/compras_sucursal
  * Lista todas las compras de una sucursal.
  *
  * Lista todas las compras de una sucursal.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.inventario.compras_sucursal.php");

$api = new ApiInventarioComprasSucursal();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
