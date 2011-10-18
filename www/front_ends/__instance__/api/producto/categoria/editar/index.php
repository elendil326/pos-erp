<?php
/**
  * GET api/producto/categoria/editar
  * Edita una categoria de producto
  *
  * Este metodo cambia la informacion de una categoria de producto
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.categoria.editar.php");

$api = new ApiProductoCategoriaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
