<?php
/**
  * GET api/servicios/orden/agregar_productos
  * Agrega uno o varios productos a una orden de servicio
  *
  * En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio. Este metodo puede ser usado apra agregar mas cantidad de un producto a uno ya existente, en este caso se ignoran los campos impuesto, descuento y retencion del arreglo de productos.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.agregar_productos.php");

$api = new ApiServiciosOrdenAgregarProductos();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
