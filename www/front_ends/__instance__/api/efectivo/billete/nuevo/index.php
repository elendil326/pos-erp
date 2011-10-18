<?php
/**
  * GET api/efectivo/billete/nuevo
  * Crea un nuevo billete
  *
  * Crea un nuevo billete, se puede utilizar para monedas tambien.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.efectivo.billete.nuevo.php");

$api = new ApiEfectivoBilleteNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
