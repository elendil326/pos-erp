<?php
/**
  * POST api/precio/regla/duplicar
  * Duplica una regla y la guarda en otra version
  *
  * Duplica una regla y la guarda en otra version. Las reglas duplicadas actualizan el id de la version a la que pertenecen y mantienen todos sus datos.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.regla.duplicar.php");

$api = new ApiPrecioReglaDuplicar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
