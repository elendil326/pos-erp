<?php
/**
  * GET api/sucursal/almacen/traspaso/enviar
  * Envia un traspaso de productos
  *
  * Cambia el estado del traspaso a enviado y captura la fecha de envio del servidor. El usuario que envia sera tomado del servidor.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.enviar.php");

$api = new ApiSucursalAlmacenTraspasoEnviar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
