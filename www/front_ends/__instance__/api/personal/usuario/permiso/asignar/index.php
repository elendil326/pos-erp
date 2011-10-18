<?php
/**
  * POST api/personal/usuario/permiso/asignar
  * Asigna unpo varios permisos especificos a un usuario
  *
  * Asigna uno o varios permisos especificos a un usuario. No se pueden asignar permisos que ya se tienen
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.permiso.asignar.php");

$api = new ApiPersonalUsuarioPermisoAsignar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
