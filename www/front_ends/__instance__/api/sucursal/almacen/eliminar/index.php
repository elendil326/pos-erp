<?php
/**
  * GET api/sucursal/almacen/eliminar
  * Elimina un almacen
  *
  * Descativa un almacen. Para poder desactivar un almacen, este tiene que estar vac?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.eliminar.php");

$api = new ApiSucursalAlmacenEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
