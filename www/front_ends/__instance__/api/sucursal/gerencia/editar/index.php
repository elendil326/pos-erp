<?php
/**
  * GET api/sucursal/gerencia/editar
  * Edita la gerencia de una sucursal
  *
  * Edita la gerencia de una sucursal
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.gerencia.editar.php");

$api = new ApiSucursalGerenciaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
