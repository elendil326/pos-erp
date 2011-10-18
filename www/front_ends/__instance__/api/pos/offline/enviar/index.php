<?php
/**
  * POST api/pos/offline/enviar
  * Enviar compras o ventas de mostrador al servidor despues de una perdida de conectividad.
  *
  * Si un perdidad de conectividad sucediera, es responsabilidad del cliente registrar las ventas o compras realizadas desde que se perdio conectividad. Cuando se restablezca la conexcion se deberan enviar las ventas o compras. 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.pos.offline.enviar.php");

$api = new ApiPosOfflineEnviar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
