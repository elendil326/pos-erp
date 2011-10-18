<?php
/**
  * GET api/efectivo/gasto/concepto/editar
  * Edita un concepto de gasto
  *
  * Edita la informaci?e un concepto de gasto

Update : Se deber?de tomar de la sesi?l id del usuario que hiso la ultima modificaci? la fecha.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.concepto.editar.php");

$api = new ApiEfectivoGastoConceptoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
