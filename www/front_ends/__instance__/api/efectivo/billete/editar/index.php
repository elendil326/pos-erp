<?php
/**
  * GET api/efectivo/billete/editar
  * Edita la informacion de un billete
  *
  * Edita la informacion de un billete
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.billete.editar.php");

$api = new ApiEfectivoBilleteEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
