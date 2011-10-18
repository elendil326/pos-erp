<?php
/**
  * GET api/proveedor/editar
  * Edita la informacion de un proveedor
  *
  * Edita la informacion de un proveedor. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.editar.php");

$api = new ApiProveedorEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
