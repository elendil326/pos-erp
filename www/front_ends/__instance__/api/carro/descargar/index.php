<?php
/**
  * GET api/carro/descargar
  * Descargar producto de un carro
  *
  * Descargar producto de un carro. El id de la sucursal se tomara de la sesion actual. La fecha se tomara del servidor. El almacen de la sucursal que realiza la operacion se vera afectada.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.carro.descargar.php");

$api = new ApiCarroDescargar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
