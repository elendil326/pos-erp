<?php
/**
  * POST api/empresa/agregar_sucursales
  * Relacionar una sucursal a esta empresa. 
  *
  * Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.empresa.agregar_sucursales.php");

$api = new ApiEmpresaAgregarSucursales();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
