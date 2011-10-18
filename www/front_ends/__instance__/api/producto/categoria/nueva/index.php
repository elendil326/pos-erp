<?php
/**
  * GET api/producto/categoria/nueva
  * Crea una nueva categoria de producto
  *
  * Crea una nueva categoria de producto, la categoria de un producto se relaciona con los meses de garantia del mismo, las unidades en las que se almacena entre, si se es suceptible a devoluciones, entre otros.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.categoria.nueva.php");

$api = new ApiProductoCategoriaNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
