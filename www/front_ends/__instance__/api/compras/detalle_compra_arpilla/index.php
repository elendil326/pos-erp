<?php
/**
  * GET api/compras/detalle_compra_arpilla
  * Muestra el detalle de una compra por arpillas
  *
  * Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

Update : Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.compras.detalle_compra_arpilla.php");

$api = new ApiComprasDetalleCompraArpilla();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
