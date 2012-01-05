<?php
/**
  * POST api/precio/tarifa/editar
  * Edita la informacion de una tarifa
  *
  * Edita la informacion de una tarifa. Este metodo puede cambiar las formulas de una tarifa o la vigencia de la misma. 

Este metodo tambien puede ponder como default esta tarifa o quitarle el default. Si se le quita el default, automaticamente se pone como default la predeterminada del sistema.
Si se obtienen formulas en este metodo, se borraran todas las formulas de esta tarifa y se aplicaran las recibidas

Si se cambia el tipo de tarifa, se verfica que esta tarifa no sea una default para algun rol, usuario, clasificacion de cliente o de proveedor, y pierde su default si fuera la default, poniendo como default la predetermianda del sistema.

Aplican todas las consideraciones de la documentacion del metodo nuevaTarifa
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.editar.php");

$api = new ApiPrecioTarifaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
