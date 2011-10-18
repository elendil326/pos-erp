<?php
/**
  * GET api/servicios/nuevo
  * Ofrecer un nuevo servicio
  *
  * Crear un nuevo concepto de servicio.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.servicios.nuevo.php");

$api = new ApiServiciosNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
