<?php
/**
  * POST api/pos/bd/drop
  * Limpiar la base de datos y establecer los valores default
  *
  * Metodo que elimina todos los registros en la base de datos, especialmente util para hacer pruebas unitarias. Este metodo NO estara disponible al publico.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.pos.bd.drop.php");

$api = new ApiPosBdDrop();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
