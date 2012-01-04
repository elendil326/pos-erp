<?php
/**
  * GET api/sucursal/almacen/tipo_almacen/eliminar
  * Elimina un tipo de almacen
  *
  * Elimina un tipo de almacen
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.tipo_almacen.eliminar.php");

$api = new ApiSucursalAlmacenTipoAlmacenEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
