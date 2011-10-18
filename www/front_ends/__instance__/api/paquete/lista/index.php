<?php
/**
  * GET api/paquete/lista
  * Lista los paquetes
  *
  * Lista los paquetes, se puede filtrar por empresa, por sucursal, por producto, por servicio y se pueden ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.paquete.lista.php");

$api = new ApiPaqueteLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
