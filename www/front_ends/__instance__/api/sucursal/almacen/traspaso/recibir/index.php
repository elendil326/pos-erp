<?php
/**
  * GET api/sucursal/almacen/traspaso/recibir
  * Recibe un traspaso de producto
  *
  * Cambia el estado de un traspaso a recibido. La  bandera de completo se prende si los productos enviados son los mismos que los recibidos. La fecha de recibo es tomada del servidor. El usuario que recibe sera tomada de la sesion actual.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.recibir.php");

$api = new ApiSucursalAlmacenTraspasoRecibir();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
