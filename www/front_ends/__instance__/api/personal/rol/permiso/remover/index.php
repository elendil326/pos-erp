<?php
/**
  * POST api/personal/rol/permiso/remover
  * Quita uno o varios permisos a un rol
  *
  * Este metodo quita un permiso de un rol. Al remover este permiso de un rol, los permisos que un usuario especifico tiene gracias a una asignacion especial se mantienen. 
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.permiso.remover.php");

$api = new ApiPersonalRolPermisoRemover();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
