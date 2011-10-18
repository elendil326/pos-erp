<?php
/**
  * GET api/autorizaciones/editar_precio_cliente
  * Solicitud para cambiar la relaci?recio-cliente
  *
  * Solicitud para cambiar la relaci?ntre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar?el servidor. El usuario y la sucursal que emiten la autorizaci?er?tomadas de la sesi?

UPDATE : Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?ara que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr? que generar mas de una autorizaci?esto implica un incremento considerable en el tiempo de respuesta y aplicaci?e los cambios.

UPDATE 2: Creo que los metodos : 
api/autorizaciones/editar_precio_cliente y api/autorizaciones/editar_siguiente_compra_venta_precio_cliente
Se podr? combinar y as?ener un solo m?do para una compra venta preferencial.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.editar_precio_cliente.php");

$api = new ApiAutorizacionesEditar_precio_cliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
