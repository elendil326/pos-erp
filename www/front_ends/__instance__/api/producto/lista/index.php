<?php
/**
  * GET api/producto/lista
  * Obtener la lista de productos.
  *
  * Se puede ordenar por los atributos de producto. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.producto.lista.php");

$api = new ApiProductoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
