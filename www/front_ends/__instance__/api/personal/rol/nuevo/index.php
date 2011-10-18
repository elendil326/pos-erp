<?php
/**
  * POST api/personal/rol/nuevo
  * Crea un nuevo grupo de usuarios
  *
  * Crea un nuevo grupo de usuarios. Se asignaran los permisos de este grupo al momento de su creacion.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.nuevo.php");

$api = new ApiPersonalRolNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
