<?php
/**
  * GET api/compras/nueva_compra_arpilla
  * Compra por arpillas
  *
  * Registra una nueva compra por arpillas. Este metodo tiene que usarse en conjunto con el metodo api/compras/nueva
Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.nueva_compra_arpilla.php");

$api = new ApiComprasNuevaCompraArpilla();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
