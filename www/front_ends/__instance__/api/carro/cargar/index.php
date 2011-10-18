<?php
/**
  * GET api/carro/cargar
  * Realizar un cargamento a un carro
  *
  * Realizar un cargamento a un carro. El id de la sucursal sera tomada de la sesion actual. La fecha sera tomada del servidor. El inventario de la sucursal que carga el camion se vera afectado por esta operacion.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.cargar.php");

$api = new ApiCarroCargar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
