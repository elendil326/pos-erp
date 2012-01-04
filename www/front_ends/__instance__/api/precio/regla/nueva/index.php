<?php
/**
  * POST api/precio/regla/nueva
  * Crea una nueva regla
  *
  * Crea una nueva regla para una version. 

Una regla que no tiene producto, categoria de producto o alguna otra relacion, es una regla que se aplica a todos los productos, servicios y paquetes.

Las secuencias de las reglas no se pueden repetir.

La formula que siguen las reglas para obtener el precio fina es la siguiente: 

       Precio Final = Precio Base * (1 + porcentaje_utilidad) + utilidad_neta

Donde :
 
    Precio Base : Es obtenido de la tarifa con la que se relaciona esta regla. 
                  Si no se relaciona con ninguna tarifa, entonces lo toma del 
                  precio o costo (dependiendo del metodo de costeo) del producto,servicio
                  o paquete.

    porcentaje_utilidad:El porcentaje de utilidad que se le ganara al precio o costo base.
                        Puede ser negativo

    utilidad_neta: La utilidad neta que se ganara al comerciar este producto,servicio o
                   paquete. Puede ser negativo.


Al asignar una tarifa base a una regla se verifica que no haya una dependencia circular.

Una misma regla puede aplicar a un producto, una clasificacion de producto, un servicio, una clasificacion de servicio y un paquete a la vez.
  *
  *
  *
  **/
require_once("../../../../../../../server/bootstrap.php");
require_once("api/api.precio.regla.nueva.php");

$api = new ApiPrecioReglaNueva();
$apiOutput = ApiOutputFormatter::getInstance();
$apiOutput->PrintOuput($api);
