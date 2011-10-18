<?php
/**
  * GET api/efectivo/billete/eliminar
  * Elimina un billete
  *
  * Desactiva un billete
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.billete.eliminar.php");

$api = new ApiEfectivoBilleteEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
