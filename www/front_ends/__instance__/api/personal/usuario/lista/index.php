<?php
/**
  * GET api/personal/usuario/lista
  * Listar a todos los usuarios del sistema.
  *
  * Listar a todos los usuarios del sistema. Se puede ordenar por los atributos del usuario y filtrar en activos e inactivos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.lista.php");

$api = new ApiPersonalUsuarioLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
