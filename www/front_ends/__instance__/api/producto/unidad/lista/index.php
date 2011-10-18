<?php
/**
  * GET api/producto/unidad/lista
  * Lista las unidades
  *
  * Lista las unidades. Se puede filtrar por activas o inactivas y ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.lista.php");

$api = new ApiProductoUnidadLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
