<?php
/**
  * GET api/precio/producto/nuevo_precio_rol
  * Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende
  *
  * Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende. 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.nuevo_precio_rol.php");

$api = new ApiPrecioProductoNuevoPrecioRol();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
