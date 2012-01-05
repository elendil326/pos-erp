<?php
/**
  *
  *
  *
  **/
	
  interface IPrecio {
  
  
	/**
 	 *
 	 *Calcula el precio de un producto, servicio o paquete. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.

Se puede especificar una tarifa para calcular el precio del articulo solo en base a este.

Si no se recibe un producto o un servicio o un paquete se devuelve un error

Se sigue la jerarquia 1-id_producto,2-id_servicio,3-id_paquete por si se recibe mas de uno de estos parametros. Por ejemplo si se recibe un id_producto y un id_paquete, el id_paquete sera ignorado y se calculara solamente el precio del producto
 	 *
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si los precios a calcular seran en base a tarifas de compra o de venta
 	 * @param cantidad float Cantidad de producto a calcular su precio. Pues existen algunas reglas que aplican solo si hay una cierta cantidad de producto
 	 * @param id_paquete int Id del paquete al cual se le desea calcular su precio
 	 * @param id_producto int Id del producto al cual se le desea calcular su precio
 	 * @param id_servicio int Id del servicio al cual se le desea calcula su precio
 	 * @param id_tarifa int Si se quiere saber el precio de un producto en una tarifa en especial se especifica con este parametro
 	 * @param id_unidad int Id de la unidad en la que esta el producto del cual se calculara el precio
 	 * @return precios json Arreglo de tarifas con sus respectivos precios
 	 **/
  static function CalcularPorArticulo
	(
		$tipo_tarifa, 
		$cantidad = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null
	);  
  
  
	
  
	/**
 	 *
 	 *Activa una tarifa previamente eliminada.
 	 *
 	 * @param id_tarifa int Id de la tarifa a activar
 	 **/
  static function ActivarTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de una tarifa. Este metodo puede cambiar las formulas de una tarifa o la vigencia de la misma. 

Este metodo tambien puede ponder como default esta tarifa o quitarle el default. Si se le quita el default, automaticamente se pone como default la predeterminada del sistema.
Si se obtienen formulas en este metodo, se borraran todas las formulas de esta tarifa y se aplicaran las recibidas

Si se cambia el tipo de tarifa, se verfica que esta tarifa no sea una default para algun rol, usuario, clasificacion de cliente o de proveedor, y pierde su default si fuera la default, poniendo como default la predetermianda del sistema.

Aplican todas las consideraciones de la documentacion del metodo nuevaTarifa
 	 *
 	 * @param id_tarifa int Id de la tarifa a editar
 	 * @param default bool Si esta tarifa sera la default
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:        
"formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacion del metodo nuevaTarifa. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula. La tarifa -1 inidica que no hay una tarifa base, sino que se toma el precio base del producto, o su costo base, segun marque su metodo de costeo.
 	 * @param id_moneda int Id de la moneda con la cual se realizaran todos los movimientos de la tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera de compra o de venta
 	 **/
  static function EditarTarifa
	(
		$id_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una tarifa. una tarifa no puede ser eliminada si es la default del sistema o si esta como default para algun usuario,rol,clasificacion de cliente o proveedor.
 	 *
 	 * @param id_tarifa int Id de la tarifa a desactivar
 	 **/
  static function EliminarTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva tarifa que le dara un precio especial a todos los productos, servicios y paquetes o solo a algunos. 

Una tarifa puede tener fechas de inicio y de fin que indican en que fechas se tomaran sus parametros. Si no se reciben fechas, se da por hecho que la tarifa no caduca. Si solo se recibe fecha de inicio, se toma como fecha de fin la maxima fecha permitida por MySQL (9999-12-31 23:59:59). Si solo se recibe fehca de fin, se toma como fecha de inicio la fecha actual del servidor.

Una tarifa puede afectar a uno o varios productos, servicios, clasificaciones de producto, clasificaciones de servicio, unidades, y/o paquetes; cada uno con los parametros de la siguiente funcion:

   Precio Final : Precio Base * (1 + porcentaje_utilidad) + utilidad_neta


Donde:


   Precio Base: Sera obtenido de la tarifa base de esta tarifa.

   porcentaje_utilidad: porcentaje de -1 a 1 de lo que se le ganara del precio base a esta tarifa.

   utilidad_neta: Ganancia neta para esta tarifa del precio base. Puede ser negativa implicando un descuento.

   Precio Final: El resultado de la formula, este valor puede ser afectado directamente por el usuario mediante los parametros metodo_redondeo, margen_min y margen_max. 

   metodo_redondeo: Es el multiplo con el cual se redondea el Precio Base despues de aplicar el porcentaje de utilidad y antes de sumar la utilidad neta. Si se quiere que todos los productos terminen en 9.99, entonces se configura el metodo_redondeo en 10 y la utilidad_neta en -0.01.

   margen_min: Es el Precio Final m?nimo permitido, si despues de realizar todos los calculos, el precio final resulta menor al valor de margen_min, se sobreecribe y se toma el valor de margen_min.

   margen_max: Es el Precio Final maximo permitido, si despues de realizar todos los calculos, el precio final resulta mayor al valor de margen_max, se sobreescribe y se toma el valor de margen_max.
   


Si no se recibe un producto, servicio, clasificacion de producto o servicio, unidad o paquete junto a estos parametros, se toma que afectara a todos los productos, servicios, clasificaciones, unidades y paquetes.

Si se recibe un producto sin unidad, entonces los parametros afectan a todos los productos sin importar su unidad, si solo se recibe una unidad sin productos, es ignorada y se toma la tarifa como que afecta a todos los productos, servicios, clasificaciones, etc.

NOTA: Se debe de tener cuidad al configurar el margen_min y margen_max pues si estos se aplican sin especificar un producto, servicio, clasificacion de producto o servicio, o paquete, aplicaran a todos los productos, servicios y paquetes.

La asignacion de una formula a algun producto, servicio, etc. requiere una secuencia, pues pueden ser afectados por mas de una formula. La secuencia indicara que formula se aplciara en lugar de otra ya almacenada.


 	 *
 	 * @param id_moneda int Id de la moneda con la que se realizaran las operaciones.
 	 * @param nombre string Nombre de la tarifa.
 	 * @param tipo_tarifa string Puede ser "venta" o "compra" e indica si la tarifa sera aplicada en las operaciones de venta o compra.
 	 * @param default bool Si esta tarifa va a ser la default del sistema o no
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:
        "formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacionde este metodo. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula.
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
  static function NuevaTarifa
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null
	);  
  
  
	
  }
