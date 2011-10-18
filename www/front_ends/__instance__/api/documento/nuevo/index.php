<?php
/**
  * GET api/documento/nuevo
  * Crea un nuevo documento
  *
  * Crea un nuevo documento.

Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.documento.nuevo.php");

$api = new ApiDocumentoNuevo();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
