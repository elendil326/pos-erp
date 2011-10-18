<?php
/**
  * GET api/efectivo/gasto/concepto/nuevo
  * Registra un nuevo concepto de gasto
  *
  * Registra un nuevo concepto de gasto

Update : En la respuesta basta con solo indicar success : true | false, y en caso de fallo indicar el por que.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.nuevo.php");

$api = new ApiEfectivoGastoConceptoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
