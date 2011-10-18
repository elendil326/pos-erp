<?php
/**
  * GET api/consignacion/inspeccion/cancelar
  * Cancela una inspeccion que aun no ha sido registrada
  *
  * Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.inspeccion.cancelar.php");

$api = new ApiConsignacionInspeccionCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
