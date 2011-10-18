<?php
/**
  * POST api/producto/nuevo/en_volumen
  * Agregar productos en volumen mediante un archivo CSV.
  *
  * Agregar productos en volumen mediante un archivo CSV.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.nuevo.en_volumen.php");

$api = new ApiProductoNuevoEn_volumen();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
