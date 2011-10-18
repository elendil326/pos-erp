<?php
/**
  * POST api/efectivo/ingreso/concepto/editar
  * Editar un concepto de ingreso
  *
  * Edita un concepto de ingreso
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.concepto.editar.php");

$api = new ApiEfectivoIngresoConceptoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
