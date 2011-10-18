<?php
/**
  * GET api/autorizaciones/gasto
  * Solicitud de autorizaci?ara realizar un gasto.
  *
  * La fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.gasto.php");

$api = new ApiAutorizacionesGasto();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
