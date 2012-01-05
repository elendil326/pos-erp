<?php
/**
  * GET api/precio/calcularPorArticulo
  * Calcula el precio de un producto
  *
  * Calcula el precio de un producto, servicio o paquete. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.

Se puede especificar una tarifa para calcular el precio del articulo solo en base a este.

Si no se recibe un producto o un servicio o un paquete se devuelve un error

Se sigue la jerarquia 1-id_producto,2-id_servicio,3-id_paquete por si se recibe mas de uno de estos parametros. Por ejemplo si se recibe un id_producto y un id_paquete, el id_paquete sera ignorado y se calculara solamente el precio del producto
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.precio.calcularPorArticulo.php");

$api = new ApiPrecioCalcularPorArticulo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
