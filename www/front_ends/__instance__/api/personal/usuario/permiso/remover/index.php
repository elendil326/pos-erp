<?php
/**
  * POST api/personal/usuario/permiso/remover
  * Quita uno o varios permisos a un usuario
  *
  * Quita uno o varios permisos a un usuario. No se puede negar un permiso que no se tiene
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.permiso.remover.php");

$api = new ApiPersonalUsuarioPermisoRemover();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
