<?php
/**
  * GET api/pos/bd/respaldar
  * Hacer un respaldo externo de la base de datos.
  *
  * Si el cliente lo desea puede respaldar toda su informacion personal. Esto descargara la base de datos y los documentos que se generan automaticamente como las facturas. Para descargar la base de datos debe tenerse un grupo de 0 o bien el permiso correspondiente.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.pos.bd.respaldar.php");

$api = new ApiPosBdRespaldar();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
