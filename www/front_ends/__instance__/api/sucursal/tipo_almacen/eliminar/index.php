<?php
/**
  * GET api/sucursal/tipo_almacen/eliminar
  * Elimina un tipo de almacen
  *
  * Elimina un tipo de almacen
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.tipo_almacen.eliminar.php");

$api = new ApiSucursalTipoAlmacenEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
