<?php
/**
  * POST api/sucursal/caja/nueva
  * Crear una caja en la sucursal
  *
  * Este metodo creara una caja asociada a una sucursal. Debe haber una caja por CPU. 
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.sucursal.caja.nueva.php");

$api = new ApiSucursalCajaNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
