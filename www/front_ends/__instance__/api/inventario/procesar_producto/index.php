<?php
/**
  * GET api/inventario/procesar_producto
  * Procesar producto no es mas que moverlo de lote.
  *
  * Procesar producto no es mas que moverlo de lote.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.inventario.procesar_producto.php");

$api = new ApiInventarioProcesarProducto();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
