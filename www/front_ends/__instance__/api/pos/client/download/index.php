<?php
/**
  * GET api/pos/client/download
  * Descargar un zip con la ultima version del cliente
  *
  * Descargar un zip con la ultima version del cliente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.pos.client.download.php");

$api = new ApiPosClientDownload();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
