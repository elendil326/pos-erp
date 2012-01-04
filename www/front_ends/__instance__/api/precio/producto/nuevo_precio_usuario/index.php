<?php
/**
  * GET api/precio/producto/nuevo_precio_usuario
  * Relaciona un usuario con uno o varios productos asignandole un precio especifico
  *
  * El precio de un producto puede varior de acuerdo al usuario al que se le venda. Este metodo relaciona uno o varios productos con un usuario mediante un precio o margen de utilidad especifico.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.nuevo_precio_usuario.php");

$api = new ApiPrecioProductoNuevoPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
