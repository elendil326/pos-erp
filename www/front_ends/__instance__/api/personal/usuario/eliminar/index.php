<?php
/**
  * POST api/personal/usuario/eliminar
  * Desactivar un usuario
  *
  * Este metodo desactiva un usuario, usese cuando un empleado ya no trabaje para usted. Que pasa cuando el usuario tiene cuentas abiertas o ventas a credito con saldo.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.usuario.eliminar.php");

$api = new ApiPersonalUsuarioEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
