<?php
/**
  * GET api/personal/rol/lista
  * Lista los roles
  *
  * Lista los roles, se puede filtrar por empresa y ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.lista.php");

$api = new ApiPersonalRolLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
