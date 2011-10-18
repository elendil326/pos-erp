<?php
/**
  * GET api/proveedor/nuevo
  * Crea un nuevo proveedor
  *
  * Crea un nuevo proveedor
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.nuevo.php");

$api = new ApiProveedorNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
