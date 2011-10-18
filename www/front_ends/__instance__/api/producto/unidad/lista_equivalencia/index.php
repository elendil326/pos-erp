<?php
/**
  * GET api/producto/unidad/lista_equivalencia
  * Lista las equivalencias existentes
  *
  * Lista las equivalencias existentes. Se puede ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.lista_equivalencia.php");

$api = new ApiProductoUnidadLista_equivalencia();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
