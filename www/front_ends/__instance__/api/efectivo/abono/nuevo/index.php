<?php
/**
  * GET api/efectivo/abono/nuevo
  * Crea un nuevo abono
  *
  * Se crea un  nuevo abono, la caja o sucursal y el usuario que reciben el abono se tomaran de la sesion. La fecha se tomara del servidor
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.abono.nuevo.php");

$api = new ApiEfectivoAbonoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
