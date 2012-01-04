<?php
/**
  * GET api/documento/nota_de_venta/imprimir
  * Imprime una nota de venta
  *
  * Imprime una nota de venta de acuerdo al id_venta y al id_impresora
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.nota_de_venta.imprimir.php");

$api = new ApiDocumentoNotaDeVentaImprimir();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
