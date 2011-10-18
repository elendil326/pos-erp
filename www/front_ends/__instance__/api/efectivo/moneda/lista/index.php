<?php
/**
  * GET api/efectivo/moneda/lista
  * Lista las moendas
  *
  * Lista las monedas de una instancia
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.moneda.lista.php");

$api = new ApiEfectivoMonedaLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
