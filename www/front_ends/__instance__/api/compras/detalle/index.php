<?php
/**
  * GET api/compras/detalle
  * Muestra el detalle de una compra
  *
  * Muestra el detalle de una compra
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.detalle.php");

$api = new ApiComprasDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
