<?php
/**
  * POST api/autorizaciones/responder
  * Responde a una auorizaci?n estado pendiente.
  *
  * Responde a una autorizaci?n estado pendiente. Este m?do no se puede aplicar a una autorizaci?a resuelta.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.responder.php");

$api = new ApiAutorizacionesResponder();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
