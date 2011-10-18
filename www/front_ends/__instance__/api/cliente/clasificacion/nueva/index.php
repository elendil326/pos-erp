<?php
/**
  * POST api/cliente/clasificacion/nueva
  * Crear una nueva clasificacion de cliente.
  *
  * Los cliente forzosamente pertenecen a una categoria. En base a esta categoria se calcula el precio que se le dara en una venta, o el descuento, o el credito.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.cliente.clasificacion.nueva.php");

$api = new ApiClienteClasificacionNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
