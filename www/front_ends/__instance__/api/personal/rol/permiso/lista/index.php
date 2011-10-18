<?php
/**
  * GET api/personal/rol/permiso/lista
  * Listar los permisos del API
  *
  * Regresa un alista de permisos, nombres y ids de los permisos del sistema.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.permiso.lista.php");

$api = new ApiPersonalRolPermisoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
