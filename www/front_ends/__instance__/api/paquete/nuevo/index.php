<?php
/**
  * GET api/paquete/nuevo
  * Crea un nuevo paquete
  *
  * Agrupa productos y/o servicios en un paquete
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.nuevo.php");

$api = new ApiPaqueteNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
