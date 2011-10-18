<?php
/**
  * GET api/servicios/lista
  * Lista todos los servicios de la instancia
  *
  * Lista todos los servicios de la instancia. Puede filtrarse por empresa, por sucursal o por activo y puede ordenarse por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.servicios.lista.php");

$api = new ApiServiciosLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
