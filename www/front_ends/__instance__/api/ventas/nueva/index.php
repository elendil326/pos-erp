<?php
/**
  * POST api/ventas/nueva
  * Genera una venta fuera de caja
  *
  * Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos.

Si hay dos productos en una misma sucursal pero disntintos almacenes entonces se intentara nivelar los almacenes al mismo valor.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.ventas.nueva.php");

$api = new ApiVentasNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
