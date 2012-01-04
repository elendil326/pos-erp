<?php
/**
  * GET api/sucursal/almacen/tipo_almacen/editar
  * Edita un tipo de almacen
  *
  * Edita un tipo de almacen
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.tipo_almacen.editar.php");

$api = new ApiSucursalAlmacenTipoAlmacenEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
