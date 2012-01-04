<?php
/**
  * POST api/procesos/nuevo
  * Define un nuevo proceso
  *
  * Define un nuevo proceso y muchas cosas mas
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.procesos.nuevo.php");

$api = new ApiProcesosNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
