<?php
/**
  * POST api/servicios/orden/seguimiento
  * Realizar un seguimiento a una orden de servicio existente
  *
  * Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.seguimiento.php");

$api = new ApiServiciosOrdenSeguimiento();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
