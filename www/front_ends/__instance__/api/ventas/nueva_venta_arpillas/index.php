<?php
/**
  * GET api/ventas/nueva_venta_arpillas
  * Realiza una nueva venta por arpillas
  *
  * Realiza una nueva venta por arpillas. Este m?todo tiene que llamarse en conjunto con el metodo api/ventas/nueva.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.ventas.nueva_venta_arpillas.php");

$api = new ApiVentasNuevaVentaArpillas();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
