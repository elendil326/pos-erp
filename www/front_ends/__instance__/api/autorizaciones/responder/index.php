<?php
/**
  * POST api/autorizaciones/responder
  * Responde a una auorizaci?n en estado pendiente.
  *
  * Responde a una autorizaci?n en estado pendiente. Este m?todo no se puede aplicar a una autorizaci?n ya resuelta.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.responder.php");

$api = new ApiAutorizacionesResponder();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
