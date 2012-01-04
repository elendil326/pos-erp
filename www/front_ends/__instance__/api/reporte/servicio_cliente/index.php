<?php
/**
  * GET api/reporte/servicio_cliente
  * Muestra una lista de los servicios que ha comprado el cliente con su cantidad
  *
  * Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.reporte.servicio_cliente.php");

$api = new ApiReporteServicioCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
