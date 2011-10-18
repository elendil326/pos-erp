<?php
/**
  * GET api/autorizaciones/compra/devolucion
  * Solicitud para devolver una compra.
  *
  * Solicitud para devolver una compra. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.compra.devolucion.php");

$api = new ApiAutorizacionesCompraDevolucion();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
