<?php
/**
  * GET api/consignacion/inspeccion/abonar
  * Abona un monto de dinero a una inspeccion ya registrada
  *
  * Abona un monto de dinero a una inspeccion ya registrada. La fecha sera tomada del sistema. Este metodo sera usado cuando el inspector llegue a la sucursal y deposite el dinero en la caja, de tal forma que se lleve un registro de cuando y cuanto deposito el dinero por el pago de la consignacion, asi como saber si el inspector ya realizo el deposito del dinero que se le consigno.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.inspeccion.abonar.php");

$api = new ApiConsignacionInspeccionAbonar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
