<?php
/**
  * POST api/precio/version/setDefault
  * Pone como default a la version obtenida para esta tarifa
  *
  * Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

Una version default no puede caducar.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.version.setDefault.php");

$api = new ApiPrecioVersionSetDefault();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
