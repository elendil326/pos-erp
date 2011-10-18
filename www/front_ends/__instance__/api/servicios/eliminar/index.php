<?php
/**
  * GET api/servicios/eliminar
  * Desactiva un servicio
  *
  * Da de baja un servicio que ofrece una empresa
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.servicios.eliminar.php");

$api = new ApiServiciosEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
