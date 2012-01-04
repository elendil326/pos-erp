<?php
/**
  * POST api/precio/version/nueva
  * Crea una nueva version para una tarifa
  *
  * Crea una nueva version para una tarifa.

Si no se reciben fechas de inicio o fin, se da por hecho que la version no caduca. Si solo se recibe fecha de fin, se toma como la fecha de inicio la fecha actual del servidor. Si solo se recibe fecha de inicio, se toma como fecha final la maxima permitida por MySQL (9999-12-31 23:59:59).

La version por default de una tarifa no puede caducar.

Las tarifas solo pueden tener una version activa.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.nueva.php");

$api = new ApiPrecioVersionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
