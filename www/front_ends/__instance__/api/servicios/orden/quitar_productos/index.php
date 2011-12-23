<?php
/**
  * GET api/servicios/orden/quitar_productos
  * Retira productos de una orden de servicio
  *
  * Este metodo se usa para quitar productos de una orden de servicio. Puede ser usado para reducir su cantidad o para retirarlo por completo
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.quitar_productos.php");

$api = new ApiServiciosOrdenQuitarProductos();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
