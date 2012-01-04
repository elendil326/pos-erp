<?php
/**
  * GET api/autorizaciones/lista
  * Lista todas las autorizaciones.
  *
  * Muestra la lista de autorizaciones, con la opci?n de filtrar por pendientes, aceptadas, rechazadas, en tr?nsito, embarques recibidos y de ordenar seg?n los atributos de autorizaciones. 
Update :  falta definir el ejemplo de envio.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.lista.php");

$api = new ApiAutorizacionesLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
