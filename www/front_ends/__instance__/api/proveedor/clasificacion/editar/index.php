<?php
/**
  * GET api/proveedor/clasificacion/editar
  * Edita la informacion de una clasificacion de proveedor
  *
  * Edita la informacion de una clasificacion de proveedor
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.clasificacion.editar.php");

$api = new ApiProveedorClasificacionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
