<?php
/**
  * GET api/autorizaciones/gasto
  * Solicitud de autorizaci?n para realizar un gasto.
  *
  * La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.gasto.php");

$api = new ApiAutorizacionesGasto();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
