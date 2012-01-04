<?php
/**
  * GET api/documento/estado_de_cuenta/imprimir
  * Imprime un estado de cuenta
  *
  * Imprime un estado de cuenta de un cliente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.documento.estado_de_cuenta.imprimir.php");

$api = new ApiDocumentoEstadoDeCuentaImprimir();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
