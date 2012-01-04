<?php
/**
  * GET api/ventas/detalle_venta_arpilla
  * Muestra el detalle de una venta por arpilla
  *
  * Muestra el detalle de una venta por arpilla. Este metodo no muestra los productos de una venta, sino los detalles del embarque de la misma. Para ver los productos de una venta refierase a api/ventas/detalle
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.ventas.detalle_venta_arpilla.php");

$api = new ApiVentasDetalleVentaArpilla();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
