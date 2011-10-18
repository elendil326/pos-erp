<?php
/**
  * GET api/inventario/ventas_sucursal
  * Lista las ventas de una sucursal
  *
  * Lista las ventas de una sucursal.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.inventario.ventas_sucursal.php");

$api = new ApiInventarioVentas_sucursal();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
