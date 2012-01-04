<?php
/**
  * POST api/autorizaciones/venta/devolucion
  * Solicitud para devolver una venta.
  *
  * Solicitud para devolver una venta. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.venta.devolucion.php");

$api = new ApiAutorizacionesVentaDevolucion();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
