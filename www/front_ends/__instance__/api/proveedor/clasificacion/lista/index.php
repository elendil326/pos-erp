<?php
/**
  * GET api/proveedor/clasificacion/lista
  * Lista las clasificaciones de proveedor
  *
  * Este emtodo lista las clasificaciones de proveedores
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.clasificacion.lista.php");

$api = new ApiProveedorClasificacionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
