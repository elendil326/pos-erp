<?php
/**
  * POST api/empresa/nuevo
  * Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
  *
  * Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.empresa.nuevo.php");

$api = new ApiEmpresaNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
