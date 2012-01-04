<?php
/**
  * GET api/efectivo/abono/editar
  * Edita un abono
  *
  * Edita la informaci?n de un abono
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.abono.editar.php");

$api = new ApiEfectivoAbonoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
