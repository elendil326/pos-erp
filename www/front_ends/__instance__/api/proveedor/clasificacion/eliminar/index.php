<?php
/**
  * GET api/proveedor/clasificacion/eliminar
  * Elimina una clasificacion de proveedor
  *
  * Desactiva una clasificacion de proveedor
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.clasificacion.eliminar.php");

$api = new ApiProveedorClasificacionEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
