<?php
/**
  * GET api/efectivo/moneda/editar
  * Edita la informacion de una moneda
  *
  * Edita la informacion de una moneda
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.moneda.editar.php");

$api = new ApiEfectivoMonedaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
