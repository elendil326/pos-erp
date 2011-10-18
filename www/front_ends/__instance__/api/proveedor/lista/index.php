<?php
/**
  * GET api/proveedor/lista
  * Obtener una lista de proveedores.
  *
  * Obtener una lista de proveedores. Puede filtrarse por activo o inactivos, y puede ordenarse por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.proveedor.lista.php");

$api = new ApiProveedorLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
