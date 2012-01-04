<?php
/**
  * POST api/precio/version/duplicar
  * Duplica la version y la guarda en otra tarifa
  *
  * Duplica la version obtenida junto con todas sus reglas y la guarda en otra tarifa. Este metodo sirve cuando una misma version con todas sus reglas aplica a mas de una tarifa.

Al duplicar una version, las reglas duplicadas con ella actualizan su id de la version a la nueva version creada.

Cuando una version activa y/o default se duplica, al guardarse en la otra tarifa pierde estas propiedades.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.duplicar.php");

$api = new ApiPrecioVersionDuplicar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
