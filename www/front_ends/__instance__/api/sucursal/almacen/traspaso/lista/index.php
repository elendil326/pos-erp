<?php
/**
  * GET api/sucursal/almacen/traspaso/lista
  * Lista los traspasos
  *
  * Lista los traspasos de almacenes. Puede filtrarse por empresa, por sucursal, por almacen, por producto, cancelados, completos, estado
  *
  *
  *
  **/
require_once("../../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.almacen.traspaso.lista.php");

$api = new ApiSucursalAlmacenTraspasoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
