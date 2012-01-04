<?php
/**
  * GET api/sucursal/caja/comprar
  * Compra de productos desde el mostrador. (caja)
  *
  * Comprar productos en mostrador. No debe confundirse con comprar productos a un proveedor. Estos productos se agregaran al inventario de esta sucursal de manera automatica e instantanea. La IP ser? tomada de la m?quina que realiza la compra. El usuario y la sucursal ser?n tomados de la sesion activa. El estado del campo liquidada ser? tomado de acuerdo al campo total y pagado.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.comprar.php");

$api = new ApiSucursalCajaComprar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
