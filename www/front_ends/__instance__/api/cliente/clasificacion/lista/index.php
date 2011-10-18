<?php
/**
  * GET api/cliente/clasificacion/lista
  * Lista de las clasificaciones existentes
  *
  * Obtener una lista de las categorias de clientes actuales en el sistema. Se puede ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.cliente.clasificacion.lista.php");

$api = new ApiClienteClasificacionLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
