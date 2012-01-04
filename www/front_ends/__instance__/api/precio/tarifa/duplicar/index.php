<?php
/**
  * POST api/precio/tarifa/duplicar
  * Duplica una tarifa
  *
  * Duplica una tarifa con todas sus versiones, y cada una de ellas con todas sus reglas. Este metodo sirve cuando se tiene una tarifa muy completa y se quiere hacer una tarifa muy similar pero con unas ligeras modificaciones.

Al duplicar la tarifa, se actualizan sus versiones default y activa por los ids generados al duplicar las versiones.

La tarifa duplicada pierde ela tributo default.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.tarifa.duplicar.php");

$api = new ApiPrecioTarifaDuplicar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
