<?php
/**
  * GET api/efectivo/gasto/editar
  * Editar los detalles de un gasto.
  *
  * Editar los detalles de un gasto.
Update :  Tambien se deberia de tomar  de la sesion el id del usuario qeu hiso al ultima modificacion y una fecha de ultima modificacion.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.gasto.editar.php");

$api = new ApiEfectivoGastoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
