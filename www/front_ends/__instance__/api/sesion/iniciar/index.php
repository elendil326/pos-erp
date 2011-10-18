<?php
/**
  * GET api/sesion/iniciar
  * Validar e iniciar una sesion.
  *
  * Valida las credenciales de un usuario y regresa un url a donde se debe de redireccionar. Este m?do no necesita de ning?ipo de autenticaci?
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar?n 403 Authorization Required y la sesi?o se iniciar?
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required supongo
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sesion.iniciar.php");

$api = new ApiSesionIniciar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
