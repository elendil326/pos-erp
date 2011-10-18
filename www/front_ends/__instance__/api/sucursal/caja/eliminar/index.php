<?php
/**
  * GET api/sucursal/caja/eliminar
  * Elimina una caja
  *
  * Desactiva una caja, para que la caja pueda ser desactivada, tieneq ue estar cerrada
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.eliminar.php");

$api = new ApiSucursalCajaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
