<?php
/**
  * GET api/sucursal/eliminar
  * Elimina una sucursal
  *
  * Desactiva una sucursal. Para poder desactivar una sucursal su saldo a favor tiene que ser mayor a cero y sus almacenes tienen que estar vacios.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.eliminar.php");

$api = new ApiSucursalEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
