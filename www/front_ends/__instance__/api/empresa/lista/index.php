<?php
/**
  * GET api/empresa/lista
  * Lista todas las empresas existentes.
  *
  * Mostrar?odas la empresas en el sistema, as?omo sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.empresa.lista.php");

$api = new ApiEmpresaLista();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
