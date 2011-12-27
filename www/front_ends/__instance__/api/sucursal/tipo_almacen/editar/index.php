<?php
/**
  * GET api/sucursal/tipo_almacen/editar
  * Edita un tipo de almacen
  *
  * Edita un tipo de almacen
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.tipo_almacen.editar.php");

$api = new ApiSucursalTipoAlmacenEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
