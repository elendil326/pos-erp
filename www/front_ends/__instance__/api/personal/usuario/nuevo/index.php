<?php
/**
  * GET api/personal/usuario/nuevo
  * Insertar un nuevo usuario.
  *
  * Insertar un nuevo usuario. El usuario que lo crea sera tomado de la sesion actual y la fecha sera tomada del servidor. Un usuario no puede tener mas de un rol en una misma sucursal de una misma empresa.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.nuevo.php");

$api = new ApiPersonalUsuarioNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
