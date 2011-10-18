<?php
/**
  * GET api/producto/unidad/nueva
  * Crea una nueva unidad
  *
  * Este metodo crea unidades, como son Kilogramos, Libras, Toneladas, Litros, costales, cajas, arpillas, etc.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.nueva.php");

$api = new ApiProductoUnidadNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
