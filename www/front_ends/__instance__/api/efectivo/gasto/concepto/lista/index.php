<?php
/**
  * GET api/efectivo/gasto/concepto/lista
  * Lista los conceptos de gasto
  *
  * Lista los conceptos de gasto. Se puede ordenar por los atributos de concepto de gasto
Update : Falta especificar los parametros y el ejemplo de envio.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.lista.php");

$api = new ApiEfectivoGastoConceptoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
