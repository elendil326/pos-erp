<?php
/**
  * POST api/servicios/orden/terminar
  * Dar por terminada una orden
  *
  * Dar por terminada una orden, al momento de terminarse una orden se genera una venta, por lo tanto, al terminar la orden hay que especificar datos de la misma.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.servicios.orden.terminar.php");

$api = new ApiServiciosOrdenTerminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
