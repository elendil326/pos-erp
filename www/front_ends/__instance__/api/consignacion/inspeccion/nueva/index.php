<?php
/**
  * GET api/consignacion/inspeccion/nueva
  * Calendariza una nueva inspeccion
  *
  * Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.inspeccion.nueva.php");

$api = new ApiConsignacionInspeccionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
