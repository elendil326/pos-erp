<?php
/**
  * GET api/efectivo/ingreso/concepto/lista
  * Lista los conceptos de ingreso
  *
  * Lista los conceptos de ingreso, se puede ordenar por los atributos del concepto de ingreso.  

Update :Falta especificar la estructura del JSON que se env?a como parametro
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.concepto.lista.php");

$api = new ApiEfectivoIngresoConceptoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
