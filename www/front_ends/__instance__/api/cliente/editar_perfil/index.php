<?php
/**
  * POST api/cliente/editar_perfil
  * Editar la informacion basica de un cliente.
  *
  * Edita la informaci?n de un cliente. El campo fecha_ultima_modificacion ser? llenado con la fecha actual del servidor. El campo Usuario_ultima_modificacion ser? llenado con la informaci?n de la sesi?n activa.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.cliente.editar_perfil.php");

$api = new ApiClienteEditarPerfil();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
