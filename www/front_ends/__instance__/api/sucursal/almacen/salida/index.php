<?php
/**
  * GET api/sucursal/almacen/salida
  * Envia productos fuera del almacen
  *
  * Envia productos fuera del almacen. Ya sea que sea un traspaso de un alamcen a otro o por motivos de inventarios fisicos.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.salida.php");

$api = new ApiSucursalAlmacenSalida();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
