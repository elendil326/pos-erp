<?php
/**
  * POST api/sucursal/almacen/nuevo
  * Crear un nuevo almacen en una sucursal
  *
  * Creara un nuevo almacen en una sucursal, este almacen contendra lotes.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.nuevo.php");

$api = new ApiSucursalAlmacenNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
