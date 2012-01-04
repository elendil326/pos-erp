<?php
/**
  * GET api/consignacion/cancelar
  * Cancela una consignacion
  *
  * Este metodo solo debe ser usado cuando no se ha vendido ningun producto de la consginacion y todos seran devueltos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.cancelar.php");

$api = new ApiConsignacionCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
