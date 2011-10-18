<?php
/**
  * GET api/proveedor/eliminar
  * Desactiva un proveedor
  *
  * Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor ??
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.eliminar.php");

$api = new ApiProveedorEliminar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
