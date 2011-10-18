<?php
/**
  * GET api/compras/cancelar
  * Cancela una compra
  *
  * Cancela una compra
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.cancelar.php");

$api = new ApiComprasCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
