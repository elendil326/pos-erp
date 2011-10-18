<?php
/**
  * POST api/sucursal/caja/vender
  * Venta de productos desde el mostrador de una sucursal.
  *
  * Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser?omada del servidor, el usuario y la sucursal ser?tomados del servidor. La ip ser?omada de la m?ina que manda a llamar al m?do. El valor del campo liquidada depender?e los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.vender.php");

$api = new ApiSucursalCajaVender();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
