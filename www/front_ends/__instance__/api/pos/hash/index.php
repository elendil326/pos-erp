<?php
/**
  * GET api/pos/hash
  * Gerenrar y /o validar un hash
  *
  * Gerenra y /o valida un hash
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.pos.hash.php");

$api = new ApiPosHash();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
