<?php
/**
  * GET api/efectivo/gasto/concepto/editar
  * Edita un concepto de gasto
  *
  * Edita la informaci?n de un concepto de gasto

Update : Se deber?a de tomar de la sesi?n el id del usuario que hiso la ultima modificaci?n y la fecha.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.editar.php");

$api = new ApiEfectivoGastoConceptoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
