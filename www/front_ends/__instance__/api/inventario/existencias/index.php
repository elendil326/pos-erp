<?php
/**
  * GET api/inventario/existencias
  * Obtener el catalogo de existencias del inventario.
  *
  * Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac?n, y lote.
Se puede ordenar por los atributos de producto. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.inventario.existencias.php");

$api = new ApiInventarioExistencias();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
