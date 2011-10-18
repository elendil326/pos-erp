<?php
/**
  * GET api/producto/unidad/nueva_equivalencia
  * Crea una equivalencia entre una unidad y otra
  *
  * Crea un registro de la equivalencia entre una unidad y otra. Ejemplo: 1 kg = 2.204 lb
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.nueva_equivalencia.php");

$api = new ApiProductoUnidadNueva_equivalencia();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
