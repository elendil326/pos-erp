<?php
/**
  * GET api/documento/lista
  * Listar documentos en el sistema.
  *
  * Lista los documentos en el sistema. Se puede filtrar por activos y por la empresa. Se puede ordenar por sus atributos
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.documento.lista.php");

$api = new ApiDocumentoLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
