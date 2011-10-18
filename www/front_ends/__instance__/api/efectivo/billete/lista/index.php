<?php
/**
  * GET api/efectivo/billete/lista
  * Lista los billetes
  *
  * Lista los billetes de una instancia
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.billete.lista.php");

$api = new ApiEfectivoBilleteLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
