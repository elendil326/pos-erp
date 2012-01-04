<?php
/**
  * GET api/producto/unidad/editar_equivalencia
  * Edita la equivalencia entre dos unidades
  *
  * Edita la equivalencia entre dos unidades.
1 kg = 2.204 lb
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.editar_equivalencia.php");

$api = new ApiProductoUnidadEditarEquivalencia();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
