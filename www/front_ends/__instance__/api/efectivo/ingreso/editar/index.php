<?php
/**
  * GET api/efectivo/ingreso/editar
  * Edita un ingreso
  *
  * Edita un ingreso

Update :El usuario y la fecha de la ultima modificaci?e deber? de obtener de la sesi?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.editar.php");

$api = new ApiEfectivoIngresoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
