<?php
/**
  * POST api/autorizaciones/venta/devolucion
  * Solicitud para devolver una venta.
  *
  * Solicitud para devolver una venta. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.venta.devolucion.php");

$api = new ApiAutorizacionesVentaDevolucion();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
