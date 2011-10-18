<?php
/**
  * GET api/paquete/detalle
  * Muestra el detalle de un paquete
  *
  * Muestra los productos y/o servicios englobados en este paquete as?omo las sucursales y las empresas donde lo ofrecen
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.detalle.php");

$api = new ApiPaqueteDetalle();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
