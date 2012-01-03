<?php
/**
  * GET api/impuestos_retenciones/impuesto/lista
  * Lista los impuestos
  *
  * Listas los impuestos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.impuesto.lista.php");

$api = new ApiImpuestosRetencionesImpuestoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
