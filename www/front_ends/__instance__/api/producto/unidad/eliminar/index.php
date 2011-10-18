<?php
/**
  * GET api/producto/unidad/eliminar
  * Elimina una unidad
  *
  * Descativa una unidad para que no sea usada por otro metodo
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.eliminar.php");

$api = new ApiProductoUnidadEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
