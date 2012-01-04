<?php
/**
  * GET api/precio/producto/editar_precio_usuario
  * Edita el precio de uno o varios productos para un cliente
  *
  * El precio de un producto puede varior de acuerdo al cliente al que se le venda. Este metodo relaciona uno o varios productos con un cliente mediante un precio o margen de utilidad especifico.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.producto.editar_precio_usuario.php");

$api = new ApiPrecioProductoEditarPrecioUsuario();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
