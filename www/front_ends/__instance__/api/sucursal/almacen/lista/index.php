<?php
/**
  * GET api/sucursal/almacen/lista
  * listar almacenes de la instancia
  *
  * listar almacenes de la isntancia. Se pueden filtrar por empresa, por sucursal, por tipo de almacen, por activos e inactivos y ordenar por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.lista.php");

$api = new ApiSucursalAlmacenLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
