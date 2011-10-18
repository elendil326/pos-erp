<?php
/**
  * POST api/empresa/eliminar
  * Desactivar una empresa.
  *
  * Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.empresa.eliminar.php");

$api = new ApiEmpresaEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
