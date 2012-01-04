<?php
/**
  * POST api/producto/nuevo
  * Crear un nuevo produco
  *
  * Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.producto.nuevo.php");

$api = new ApiProductoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
