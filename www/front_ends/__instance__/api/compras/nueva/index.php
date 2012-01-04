<?php
/**
  * POST api/compras/nueva
  * Registra una nueva compra
  *
  * Registra una nueva compra, la compra puede ser a cualquier usuario, siendo este un cliente, proveedor, o cualquiera. La compra siempre viene acompa?anda de un detalle de productos que han sido comprados, y cada uno estipula a que almacen y a que sucursal iran a parar.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.nueva.php");

$api = new ApiComprasNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
