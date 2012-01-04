<?php
/**
  * GET api/precio/regla/lista
  * Lista las reglas de existentes
  *
  * Lista las reglas existentes. Puede filtrarse por la version, por producto, por unidad, por categoria de producto o servicio, por servicio o por paquete, por tarifa base o por alguna combinacion de ellos.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.regla.lista.php");

$api = new ApiPrecioReglaLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
