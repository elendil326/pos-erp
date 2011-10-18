<?php
/**
  * GET api/sucursal/almacen/editar
  * Edita la informacion de un almacen
  *
  * Edita la informacion de un almacen
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.editar.php");

$api = new ApiSucursalAlmacenEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
