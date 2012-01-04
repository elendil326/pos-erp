<?php
/**
  * POST api/precio/version/activar
  * Activa una version
  *
  * Activa una version. Como solo puede haber una version activa por tarifa, este metodo desactiva la version actualmente activa de la tarifa y activa la version obtenida como parametro.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.activar.php");

$api = new ApiPrecioVersionActivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
