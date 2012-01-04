<?php
/**
  * POST api/sesion/iniciar
  * Validar e iniciar una sesion.
  *
  * Valida las credenciales de un usuario. Este m?todo no necesita de ning?n tipo de autenticaci?n. 
Si se detecta un tipo de usuario inferior a admin y no se ha llamado antes a api/sucursal/revisar_sucursal se regresar? un 403 Authorization Required y la sesi?n no se iniciar?.
Si el usuario que esta intentando iniciar sesion, esta descativado... 403 Authorization Required.

Si request_token se envia verdadero no se asociara una cookie a esta peticion, sino que se regresara un token que debera ser enviado en cada llamada subsecuente de este cliente. Los tokens expiraran segun la configuracion del sistema.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.sesion.iniciar.php");

$api = new ApiSesionIniciar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
