<?php
/**
  * GET api/consignacion/editar
  * Edita una consignacion
  *
  * Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.editar.php");

$api = new ApiConsignacionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
