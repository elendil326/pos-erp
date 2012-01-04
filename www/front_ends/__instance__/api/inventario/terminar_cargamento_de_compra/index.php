<?php
/**
  * GET api/inventario/terminar_cargamento_de_compra
  * ver transporte y fletes...
  *
  * ver transporte y fletes...
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.inventario.terminar_cargamento_de_compra.php");

$api = new ApiInventarioTerminarCargamentoDeCompra();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
