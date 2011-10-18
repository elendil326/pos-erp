<?php
/**
  * GET api/sucursal/caja/calcular_saldo_esperado
  * Calcula el saldo esperado para una caja
  *
  * Calcula el saldo esperado para una caja a partir de los cortes que le han realizado, la fecha de apertura y la fecha en que se realiza el calculo. La caja sera tomada de la sesion, la fecha sera tomada del servidor. Para poder realizar este metodo, la caja tiene que estar abierta
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.calcular_saldo_esperado.php");

$api = new ApiSucursalCajaCalcular_saldo_esperado();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
