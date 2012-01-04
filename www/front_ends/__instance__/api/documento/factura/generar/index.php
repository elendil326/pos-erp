<?php
/**
  * GET api/documento/factura/generar
  * Genera una factura
  *
  * Genera una factura seg?n la informaci?n de un cliente y la venta realizada.

Update : Falta especificar si seria una factura detallada (cuando en los conceptos de la factura describe a cada articulo) o generica (un solo concepto que engloba a todos los productos).
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.factura.generar.php");

$api = new ApiDocumentoFacturaGenerar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
