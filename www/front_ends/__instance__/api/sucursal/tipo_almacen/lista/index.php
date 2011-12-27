<?php
/**
  * GET api/sucursal/tipo_almacen/lista
  * Imprime la lista de tipos de almacen
  *
  * Imprime la lista de tipos de almacen
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.tipo_almacen.lista.php");

$api = new ApiSucursalTipoAlmacenLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
