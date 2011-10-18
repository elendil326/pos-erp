<?php
/**
  * GET api/producto/categoria/desactivar
  * Desactiva una categoria
  *
  * Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.categoria.desactivar.php");

$api = new ApiProductoCategoriaDesactivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
