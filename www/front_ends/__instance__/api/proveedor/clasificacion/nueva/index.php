<?php
/**
  * GET api/proveedor/clasificacion/nueva
  * Crea una nueva clasificacion de proveedor
  *
  * Crea una nueva clasificacion de proveedor
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.clasificacion.nueva.php");

$api = new ApiProveedorClasificacionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
