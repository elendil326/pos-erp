<?php
/**
  * GET api/impuestos_retenciones/impuesto/nuevo
  * Crear un nuevo impuesto.
  *
  * Crear un nuevo impuesto.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.impuestos_retenciones.impuesto.nuevo.php");

$api = new ApiImpuestos_retencionesImpuestoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
