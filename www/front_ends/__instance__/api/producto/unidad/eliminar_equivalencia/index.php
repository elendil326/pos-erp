<?php
/**
  * GET api/producto/unidad/eliminar_equivalencia
  * Elimina una equivalencia
  *
  * Elimina una equivalencia entre dos unidades.
Ejemplo: 1 kg = 2.204 lb
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.eliminar_equivalencia.php");

$api = new ApiProductoUnidadEliminar_equivalencia();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
