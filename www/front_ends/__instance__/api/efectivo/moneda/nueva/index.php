<?php
/**
  * GET api/efectivo/moneda/nueva
  * Crea una nueva moneda
  *
  * Crea una moneda, "pesos", "dolares", etc.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.moneda.nueva.php");

$api = new ApiEfectivoMonedaNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
