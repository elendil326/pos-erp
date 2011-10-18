<?php
/**
  * GET api/consignacion/terminar
  * Lista todas las empresas existentes.
  *
  * Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.terminar.php");

$api = new ApiConsignacionTerminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
