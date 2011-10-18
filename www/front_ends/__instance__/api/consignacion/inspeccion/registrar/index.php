<?php
/**
  * GET api/consignacion/inspeccion/registrar
  * Registra la inspeccion realizada a una consignacion
  *
  * Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.inspeccion.registrar.php");

$api = new ApiConsignacionInspeccionRegistrar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
