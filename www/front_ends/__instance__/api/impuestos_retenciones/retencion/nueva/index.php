<?php
/**
  * GET api/impuestos_retenciones/retencion/nueva
  * Crea una nueva retencion
  *
  * Crea una nueva retencion
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.retencion.nueva.php");

$api = new ApiImpuestosRetencionesRetencionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
