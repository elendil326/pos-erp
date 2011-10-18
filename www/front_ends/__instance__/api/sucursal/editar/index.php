<?php
/**
  * GET api/sucursal/editar
  * Edita una sucursal
  *
  * Edita los datos de una sucursal
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.editar.php");

$api = new ApiSucursalEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
