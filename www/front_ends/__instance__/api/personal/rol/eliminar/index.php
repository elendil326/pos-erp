<?php
/**
  * POST api/personal/rol/eliminar
  * Elimina un grupo
  *
  * Este metodo desactiva un grupo, solo se podra desactivar un grupo si no hay ningun usuario que pertenezca a ?l.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.personal.rol.eliminar.php");

$api = new ApiPersonalRolEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
