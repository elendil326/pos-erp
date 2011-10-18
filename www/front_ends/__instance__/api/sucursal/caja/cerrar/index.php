<?php
/**
  * GET api/sucursal/caja/cerrar
  * Hace un corte en los flujos de dinero de esta caja
  *
  * Hace un corte en los flujos de dinero de la sucursal. El Id de la sucursal se tomara de la sesion actual. La fehca se tomara del servidor.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.cerrar.php");

$api = new ApiSucursalCajaCerrar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
