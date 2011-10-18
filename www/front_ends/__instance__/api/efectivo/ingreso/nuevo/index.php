<?php
/**
  * GET api/efectivo/ingreso/nuevo
  * Registra un nuevo ingreso
  *
  * Registra un nuevo ingreso
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.ingreso.nuevo.php");

$api = new ApiEfectivoIngresoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
