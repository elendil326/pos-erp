<?php
/**
  * POST api/precio/version/editar
  * Edita una version de una tarifa
  *
  * Edita la informacion basica de una version. El nombre, la fecha de inicio y la fecha de fin.

?Sera necesario permitir que el usuario cambie una version de una tarifa a otra tarifa?
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.editar.php");

$api = new ApiPrecioVersionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
