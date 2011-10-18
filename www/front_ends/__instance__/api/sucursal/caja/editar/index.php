<?php
/**
  * GET api/sucursal/caja/editar
  * Edita la informacion de una caja
  *
  * Edita la informacion de una caja
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.editar.php");

$api = new ApiSucursalCajaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
