<?php
/**
  * GET api/impuestos_retenciones/retencion/lista
  * Lista las retenciones
  *
  * Lista las retenciones
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.retencion.lista.php");

$api = new ApiImpuestosRetencionesRetencionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
