<?php
/**
  * POST api/sucursal/almacen/entrada
  * Surtir una sucursal
  *
  * Metodo que surte una sucursal por parte de un proveedor. La sucursal sera tomada de la sesion actual.

Update
Creo que este metodo tiene que estar bajo sucursal.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.entrada.php");

$api = new ApiSucursalAlmacenEntrada();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
