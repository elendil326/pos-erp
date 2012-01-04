<?php
/**
  * GET api/personal/usuario/permiso/lista
  * Lista los permisos con sus usuarios
  *
  * Lista los permisos con los usuarios asigandos. Puede filtrarse por id_usuario o id_rol
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.permiso.lista.php");

$api = new ApiPersonalUsuarioPermisoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
