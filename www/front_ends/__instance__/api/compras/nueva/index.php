<?php
/**
  * GET api/compras/nueva
  * Registra una nueva compra fuera de caja
  *
  * Registra una nueva compra fuera de caja, puede usarse para que el administrador haga directamente una compra. El usuario y al sucursal seran tomados de la sesion. La fecha sera tomada del servidor. La empresa sera tomada del almacen del cual fueron tomados los productos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.nueva.php");

$api = new ApiComprasNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
