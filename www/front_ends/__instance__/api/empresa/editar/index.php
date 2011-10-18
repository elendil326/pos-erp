<?php
/**
  * GET api/empresa/editar
  * Edita una empresa existente.
  *
  * Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.empresa.editar.php");

$api = new ApiEmpresaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
