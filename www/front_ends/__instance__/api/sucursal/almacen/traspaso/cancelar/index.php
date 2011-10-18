<?php
/**
  * GET api/sucursal/almacen/traspaso/cancelar
  * Cancela un traspaso
  *
  * Para poder cancelar un traspaso, este no tuvo que haber sido enviado aun.
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.cancelar.php");

$api = new ApiSucursalAlmacenTraspasoCancelar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
