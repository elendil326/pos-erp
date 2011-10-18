<?php
/**
  * GET api/producto/unidad/editar
  * Edita una unidad
  *
  * Este metodo modifica la informacion de una unidad
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.producto.unidad.editar.php");

$api = new ApiProductoUnidadEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
