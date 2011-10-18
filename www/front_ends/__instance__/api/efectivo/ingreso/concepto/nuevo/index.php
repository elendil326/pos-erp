<?php
/**
  * GET api/efectivo/ingreso/concepto/nuevo
  * Crear un nuevo concepto de ingreso
  *
  * Crea un nuevo concepto de ingreso

Update : En la respuesta basta con solo indicar success : true | false, y en caso de fallo indicar el por que.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.concepto.nuevo.php");

$api = new ApiEfectivoIngresoConceptoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
