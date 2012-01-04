<?php
/**
  * GET api/autorizaciones/compra/devolucion
  * Solicitud para devolver una compra.
  *
  * Solicitud para devolver una compra. La fecha de petici?n se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.compra.devolucion.php");

$api = new ApiAutorizacionesCompraDevolucion();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
