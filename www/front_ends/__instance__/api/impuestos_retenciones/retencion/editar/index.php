<?php
/**
  * GET api/impuestos_retenciones/retencion/editar
  * Edita una retencion
  *
  * Edita la informacion de una retencion
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.retencion.editar.php");

$api = new ApiImpuestosRetencionesRetencionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
