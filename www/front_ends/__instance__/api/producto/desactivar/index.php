<?php
/**
  * GET api/producto/desactivar
  * Desactiva un producto
  *
  * Este metodo sirve para dar de baja un producto
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.producto.desactivar.php");

$api = new ApiProductoDesactivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
