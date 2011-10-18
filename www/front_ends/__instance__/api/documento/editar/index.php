<?php
/**
  * GET api/documento/editar
  * Edita un documento
  *
  * Update : Falta indicar en los argumentos el si el documeto esta activo y a que sucursal pertenece.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.documento.editar.php");

$api = new ApiDocumentoEditar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
