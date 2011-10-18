<?php
/**
  * GET api/sucursal/caja/corte
  * Realiza un corte de caja
  *
  * Realiza un corte de caja. Este metodo reduce el dinero de la caja y va registrando el dinero acumulado de esa caja. Si faltase dinero se carga una deuda al cajero. La fecha sera tomada del servidor. El usuario sera tomado de la sesion.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.corte.php");

$api = new ApiSucursalCajaCorte();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
