<?php
/**
  * GET api/autorizaciones/solicitar_producto
  * Solicitud de un producto
  *
  * Solicitud de un producto, la fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.
Update :  Me parece que no es buena idea manejar en los argumentos solo un id_producto y cantidad, creo que seria mejor manejar un array de objetos producto, que tuvieran como propiedades el id del producto y la cantidad solicitada, ya que si por ejemplo llega un cliente grande y necesita mas de un producto, y no pudiera cubrir la cantidad solicitada, por cada producto tendr?as que solicitar una autorizaci?n.
 
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.solicitar_producto.php");

$api = new ApiAutorizacionesSolicitarProducto();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
