<?php
/**
  * GET api/documento/factura/cancelar
  * Cancela una factura
  *
  * Cancela una factura.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.factura.cancelar.php");

$api = new ApiDocumentoFacturaCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
