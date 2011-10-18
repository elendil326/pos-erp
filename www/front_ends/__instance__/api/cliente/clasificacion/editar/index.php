<?php
/**
  * GET api/cliente/clasificacion/editar
  * Edita la clasificacion de cliente
  *
  * Edita la informacion de la clasificacion de cliente
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.cliente.clasificacion.editar.php");

$api = new ApiClienteClasificacionEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
