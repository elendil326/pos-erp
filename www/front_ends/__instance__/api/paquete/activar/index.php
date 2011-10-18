<?php
/**
  * GET api/paquete/activar
  * Activa un paquete
  *
  * Activa un paquete previamente desactivado
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.activar.php");

$api = new ApiPaqueteActivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
