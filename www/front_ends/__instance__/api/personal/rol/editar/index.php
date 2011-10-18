<?php
/**
  * POST api/personal/rol/editar
  * Edita un grupo
  *
  * Edita la informacion de un grupo, puede usarse para editar los permisos del mismo
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.editar.php");

$api = new ApiPersonalRolEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
