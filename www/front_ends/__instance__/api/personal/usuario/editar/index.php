<?php
/**
  * GET api/personal/usuario/editar
  * Editar los detalles de un usuario.
  *
  * Editar los detalles de un usuario.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.editar.php");

$api = new ApiPersonalUsuarioEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
