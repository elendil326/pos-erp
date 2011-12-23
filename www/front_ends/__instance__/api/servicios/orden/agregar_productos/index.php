<?php
/**
  * GET api/servicios/orden/agregar_productos
  * Agrega uno o varios productos a una orden de servicio
  *
  * En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.agregar_productos.php");

$api = new ApiServiciosOrdenAgregarProductos();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
