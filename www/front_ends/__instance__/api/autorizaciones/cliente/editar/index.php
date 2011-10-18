<?php
/**
  * POST api/autorizaciones/cliente/editar
  * Solicitar autorizacion para editar algun campo de un cliente.
  *
  * Solicitud para cambiar alg?ato de un cliente. La fecha de petici?e tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?

La autorizacion se guardara con los datos del usuario que la pidio. Si es aceptada, entonces el usuario podra editar al cliente una vez.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.cliente.editar.php");

$api = new ApiAutorizacionesClienteEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
