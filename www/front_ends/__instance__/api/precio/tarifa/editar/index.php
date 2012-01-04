<?php
/**
  * POST api/precio/tarifa/editar
  * Edita la informacion de una tarifa
  *
  * Edita la informacion b?sica de una tarifa, su nombre, su tipo de tarifa o su moneda. Si se edita el tipo de tarifa se tiene que verificar que esta tarifa no este siendo usada por default en una tarifa de su tipo anterior. 

Ejemplo: La tarifa 1 es tarifa de compra, el usuario 1 tiene como default de tarifa de compra la tarifa 1. Si queremos editar el tipo de tarifa de la tarifa 1 a una tarifa de venta tendra que mandar error, especificando que la tarifa esta siendo usada como tarifa de compra por el usuario 1.

Los parametros que no sean explicitamente nulos seran tomados como edicion.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.editar.php");

$api = new ApiPrecioTarifaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
