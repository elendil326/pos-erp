<?php
/**
  * GET api/compras/lista
  * Lista las compras
  *
  * Lista las compras. Se puede filtrar por empresa, sucursal, caja, usuario que registra la compra, usuario al que se le compra, tipo de compra, si estan pagadas o no, por tipo de pago, canceladas o no, por el total, por fecha, por el tipo de pago y se puede ordenar por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.lista.php");

$api = new ApiComprasLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
