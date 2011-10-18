<?php
/**
  * GET api/impuestos_retenciones/impuesto/editar
  * Edita un impuesto
  *
  * Edita la informacion de un impuesto
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.impuesto.editar.php");

$api = new ApiImpuestos_retencionesImpuestoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
