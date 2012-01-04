<?php
/**
  * POST api/sucursal/caja/vender
  * Venta de productos desde el mostrador de una sucursal.
  *
  * Vender productos desde el mostrador de una sucursal. Cualquier producto vendido aqui sera descontado del inventario de esta sucursal. La fecha ser? tomada del servidor, el usuario y la sucursal ser?n tomados del servidor. La ip ser? tomada de la m?quina que manda a llamar al m?todo. El valor del campo liquidada depender? de los campos total y pagado. La empresa se tomara del alamcen de donde salieron los productos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.vender.php");

$api = new ApiSucursalCajaVender();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
