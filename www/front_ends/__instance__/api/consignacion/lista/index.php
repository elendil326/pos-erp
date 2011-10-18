<?php
/**
  * GET api/consignacion/lista
  * Lista de cosnignaciones 
  *
  * Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.lista.php");

$api = new ApiConsignacionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
