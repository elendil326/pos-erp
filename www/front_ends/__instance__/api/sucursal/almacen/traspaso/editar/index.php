<?php
/**
  * GET api/sucursal/almacen/traspaso/editar
  * Edita la informacion de un traspaso
  *
  * Para poder editar un traspaso,este no tuvo que haber sido enviado aun
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.editar.php");

$api = new ApiSucursalAlmacenTraspasoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
