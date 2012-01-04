<?php
/**
  * POST api/precio/regla/editar
  * Edita la informaciond e una regla
  *
  * Edita la informacion basica de una regla. 

Los parametros recibidos seran tomados para edicion.

?Sera necesario dar la oportunidad al usuario de cambiar la version a la que pertence la regla?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.regla.editar.php");

$api = new ApiPrecioReglaEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
