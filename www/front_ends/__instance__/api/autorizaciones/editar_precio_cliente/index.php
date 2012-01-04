<?php
/**
  * GET api/autorizaciones/editar_precio_cliente
  * Solicitud para cambiar la relaci?n precio-cliente
  *
  * Solicitud para cambiar la relaci?n entre cliente y el precio ofrecido para cierto producto ya sea en compra o en venta. La fecha de peticion se tomar? del servidor. El usuario y la sucursal que emiten la autorizaci?n ser?n tomadas de la sesi?n.

UPDATE : Actualmente como se maneja esto es por medio de las ventas preferenciales, es decir, se manda una autorizaci?n para que el cajero pueda editar todos los precios que desee, de todos los productos "solo para esa venta y solo para ese cliente especificamente", ya que si el cliente quisiera que le vendieran mas de un solo producto a diferente precio tendr?as que generar mas de una autorizaci?n, esto implica un incremento considerable en el tiempo de respuesta y aplicaci?n de los cambios.

UPDATE 2: Creo que los metodos : 
api/autorizaciones/editar_precio_cliente y api/autorizaciones/editar_siguiente_compra_venta_precio_cliente
Se podr?an combinar y as? tener un solo m?todo para una compra venta preferencial.
  *
  *
  *
  **/
require_once("../../../../../../server/bootstrap.php");
require_once("api/api.autorizaciones.editar_precio_cliente.php");

$api = new ApiAutorizacionesEditarPrecioCliente();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
