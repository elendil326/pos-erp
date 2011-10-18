<?php
/**
  * POST api/personal/rol/permiso/asignar
  * Asigna permisos a un rol
  *
  * Este metodo asigna permisos a un rol. Cada vez que se llame a este metodo, se asignaran estos permisos a los usuarios que pertenezcan a este rol.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.permiso.asignar.php");

$api = new ApiPersonalRolPermisoAsignar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
