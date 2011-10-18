<?php
/**
  * GET api/consignacion/consignatario/desactivar
  * Desactiva un consignatario
  *
  * Desactiva la bandera de consignatario a un cliente y elimina su almacen correspondiente. Para poder hacer esto, el almacen debera estar vacio.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.consignacion.consignatario.desactivar.php");

$api = new ApiConsignacionConsignatarioDesactivar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
