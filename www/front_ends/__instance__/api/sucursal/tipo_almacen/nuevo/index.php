<?php
/**
  * GET api/sucursal/tipo_almacen/nuevo
  * Crea un nuevo tipo de almacen
  *
  * Crea un nuevo tipo de almacen
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.tipo_almacen.nuevo.php");

$api = new ApiSucursalTipoAlmacenNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
