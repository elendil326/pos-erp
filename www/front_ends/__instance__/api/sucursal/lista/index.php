<?php
/**
  * GET api/sucursal/lista
  * Lista todas las sucursales existentes.
  *
  * Lista las sucursales relacionadas con esta instancia. Se puede filtrar por empresa,  saldo inferior o superior a, fecha de apertura, ordenar por fecha de apertura u ordenar por saldo. Se agregar?n link en cada una para poder acceder a su detalle.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.lista.php");

$api = new ApiSucursalLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
