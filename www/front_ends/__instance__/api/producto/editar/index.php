<?php
/**
  * GET api/producto/editar
  * Edita un producto
  *
  * Edita la informaci?n de un producto
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.producto.editar.php");

$api = new ApiProductoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
