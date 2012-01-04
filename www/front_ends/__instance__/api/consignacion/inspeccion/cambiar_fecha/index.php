<?php
/**
  * GET api/consignacion/inspeccion/cambiar_fecha
  * Cambia la fecha de una inspeccion 
  *
  * Usese este metodo cuando se posterga o se adelanta una inspeccion
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.inspeccion.cambiar_fecha.php");

$api = new ApiConsignacionInspeccionCambiarFecha();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
