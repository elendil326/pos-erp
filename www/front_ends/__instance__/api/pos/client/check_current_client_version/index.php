<?php
/**
  * GET api/pos/client/check_current_client_version
  * Revisar la version que esta actualmente en el servidor
  *
  * Revisar la version que esta actualmente en el servidor. 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.pos.client.check_current_client_version.php");

$api = new ApiPosClientCheck_current_client_version();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
