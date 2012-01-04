<?php
/**
  *
  *
  *
  **/
	
  interface IPrecio {
  
  
	/**
 	 *
 	 *Duplica una regla y la guarda en otra version. Las reglas duplicadas actualizan el id de la version a la que pertenecen y mantienen todos sus datos.
 	 *
 	 * @param id_regla int Id de la regla a duplicar
 	 * @param id_version int Id de la version a la cual se duplicara la regla
 	 * @return id_regla int Id de la regla creada
 	 **/
  static function DuplicarRegla
	(
		$id_regla, 
		$id_version
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion basica de una regla. 

Los parametros recibidos seran tomados para edicion.

?Sera necesario dar la oportunidad al usuario de cambiar la version a la que pertence la regla?
 	 *
 	 * @param id_regla int Id de la regla a editar
 	 * @param cantidad_minima int Cantidad minima de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la categoria del producto sobre la cual actuara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio sobre la cual actuara esta regla
 	 * @param id_paquete int Id del paquete sobre el cual actuara esta regla
 	 * @param id_producto int Id del producto sobre el cual actuara esta regla
 	 * @param id_servicio int Id del servicio sobre el cual actuara esta regla
 	 * @param id_tarifa int Id de la tarifa de donde se obtendra el precio base
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Falta definir por Manuel
 	 * @param margen_min float Falta definir por Manuel
 	 * @param metodo_redondeo float Falta definir por Manuel
 	 * @param nombre string Nombre del usuario
 	 * @param porcentaje_utilidad float Porncetaje de utilidad que se ganara al comercia con este objeto
 	 * @param secuencia int Numero de secuencia de esta regla
 	 * @param utilidad_neta float Utilidad neta que s eganara al comerciar con este objeto
 	 **/
  static function EditarRegla
	(
		$id_regla, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = null, 
		$margen_min = null, 
		$metodo_redondeo = null, 
		$nombre = null, 
		$porcentaje_utilidad = null, 
		$secuencia = null, 
		$utilidad_neta = null
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina una regla. La regla por default de l aversion por default de la tarifa por default no puede ser eliminada
 	 *
 	 * @param id_regla int Id de la regla a eliminar
 	 **/
  static function EliminarRegla
	(
		$id_regla
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las reglas existentes. Puede filtrarse por la version, por producto, por unidad, por categoria de producto o servicio, por servicio o por paquete, por tarifa base o por alguna combinacion de ellos.
 	 *
 	 * @param id_clasificacion_producto int Si se recibe este parametro, se lsitaran las reglas que afectan a esta clasificacion de producto
 	 * @param id_clasificacion_servicio int Si se recibe este parametro, se listaran las reglas que afecten a esta clasificacion de servicio
 	 * @param id_paquete int Si se recibe este parametro, se listaran las reglas que afecten a este paquete
 	 * @param id_producto int Si se recibe este parametro se listaran las reglas que afectan a este producto
 	 * @param id_servicio int Si se recibe este parametro se listaran las reglas que afecten a este servicio
 	 * @param id_tarifa int Si se recibe este parametro, se listaran las reglas que se basen en esta tarifa
 	 * @param id_unidad int Si se recibe este parametro, se listaran las reglas que afecten a esta unidad
 	 * @param id_version int Si se obtiene este parametro se listaran las relas de esta version
 	 * @return lista_reglas int Arreglo de reglas
 	 **/
  static function ListaRegla
	(
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$id_version = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva regla para una version. 

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
 	 * @param id_version int Id de la version a la que pertenecera esta regla
 	 * @param nombre string Nombre de la regla
 	 * @param secuencia int Numero de secuencia de la regla, sirve para definir prioridades entre las reglas.
 	 * @param cantidad_minima int Cantidad minima que debe cumplirse de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la clasificacion del producto a la que se le aplicara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio a la cual se le aplicara esta regla
 	 * @param id_paquete int Id del paquete al cal se le aplicara esta regla
 	 * @param id_producto int Id del producto al que se le aplicara esta regla
 	 * @param id_servicio int Id del servicio al cual se le aplicara esta regla
 	 * @param id_tarifa int Id de la tarifa que se usara para determinar el precio base del objeto
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Pendiente descripcion por Manuel
 	 * @param margen_min float Pendiente descripcion por Manuel
 	 * @param metodo_redondeo float Pendiente descricpion por manuel
 	 * @param porcentaje_utilidad float Porcentaje de utilidad, va de -1 a 1
 	 * @param utilidad_neta float La utilidad neta que se ganara, puede ser negativa indicando un descuento
 	 * @return id_regla int Id de la regla creada
 	 **/
  static function NuevaRegla
	(
		$id_version, 
		$nombre, 
		$secuencia, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = 0, 
		$margen_min = 0, 
		$metodo_redondeo = 0, 
		$porcentaje_utilidad = 0, 
		$utilidad_neta = 0
	);  
  
  
	
  
	/**
 	 *
 	 *Activa una tarifa preciamente eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a activar
 	 **/
  static function ActivarTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Duplica una tarifa con todas sus versiones, y cada una de ellas con todas sus reglas. Este metodo sirve cuando se tiene una tarifa muy completa y se quiere hacer una tarifa muy similar pero con unas ligeras modificaciones.

Al duplicar la tarifa, se actualizan sus versiones default y activa por los ids generados al duplicar las versiones.

La tarifa duplicada pierde ela tributo default.
 	 *
 	 * @param id_moneda int Id de la moneda que aplicara para la nueva tarifa
 	 * @param id_tarifa int Id de la tarifa a duplicar
 	 * @param nombre string Nombre de la nueva tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la nueva tarifa sera aplicada para compras o ventas
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
  static function DuplicarTarifa
	(
		$id_moneda, 
		$id_tarifa, 
		$nombre, 
		$tipo_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion b?sica de una tarifa, su nombre, su tipo de tarifa o su moneda. Si se edita el tipo de tarifa se tiene que verificar que esta tarifa no este siendo usada por default en una tarifa de su tipo anterior. 

Ejemplo: La tarifa 1 es tarifa de compra, el usuario 1 tiene como default de tarifa de compra la tarifa 1. Si queremos editar el tipo de tarifa de la tarifa 1 a una tarifa de venta tendra que mandar error, especificando que la tarifa esta siendo usada como tarifa de compra por el usuario 1.

Los parametros que no sean explicitamente nulos seran tomados como edicion.
 	 *
 	 * @param id_tarifa int Id de la tarifa que se va a editar
 	 * @param id_moneda int Id de la moneda con la que se manejaran las operaciones de esta tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera usada en las operaciones de compra o de venta
 	 **/
  static function EditarTarifa
	(
		$id_tarifa, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una tarifa. Para poder desactivar una tarifa, esta no tiene que estar asignada como default para ningun usuario. La tarifa default del sistema no puede ser eliminada.

La tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a eliminar
 	 **/
  static function EliminarTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las tarifas existentes. Se puede ordenar de acuerdo a los atributos de la tabla y se puede filtrar por el tipo de tarifa, la moneda que usa y por el valor de activa.
 	 *
 	 * @param activa bool Si este valor es obtenido, se listaran las tarifas que cuyo valor de activa sea como el obtenido
 	 * @param id_moneda int Si es obtenido este valor, se listaran las tarifas que tengan el valor de moneda como el obtenido.
 	 * @param orden string El nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @param tipo_tarifa string Si es obtenido, se listaran las tarifas que tengan el mismo valor de tipo de tarifa que este.
 	 * @return lista_tarifas json Arreglo de tarifas
 	 **/
  static function ListaTarifa
	(
		$activa = null, 
		$id_moneda = null, 
		$orden = null, 
		$tipo_tarifa = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva tarifa 
 	 *
 	 * @param id_moneda int Id de la moneda en la que se manejaran los valores de precios en esta tarifa
 	 * @param nombre string nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" y sirve para identificar si la tarifa se aplicara en compras o en ventas
 	 * @param activa bool Si esta tarifa estara activa al momento de su creacion
 	 * @param default bool Si se quiere que esta tarifa sea la default del sistema
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
  static function NuevaTarifa
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$activa = null, 
		$default = null
	);  
  
  
	
  
	/**
 	 *
 	 *Asigna el default a una tarifa de compra. La tarifa default es la que se usara en todas las compras a menos que el usuario indique otra tarifa.

Solo se puede elegir una tarifa de tipo compra.
 	 *
 	 * @param id_tarifa int Id de la tarifa de compra a elegir como default del sistema
 	 **/
  static function CompraSetDefaultTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Selecciona como default para las ventas una tarifa de venta. Esta tarifa sera usada para todas las ventas a menos que el usuario indique otra tarifa de venta.

Solo puede asignarse como default de ventas una tarifa de tipo venta
 	 *
 	 * @param id_tarifa int Id de la tarifa a poner como default
 	 **/
  static function VentaSetDefaultTarifa
	(
		$id_tarifa
	);  
  
  
	
  
	/**
 	 *
 	 *Activa una version. Como solo puede haber una version activa por tarifa, este metodo desactiva la version actualmente activa de la tarifa y activa la version obtenida como parametro.
 	 *
 	 * @param id_version int Id de la version a activar
 	 **/
  static function ActivarVersion
	(
		$id_version
	);  
  
  
	
  
	/**
 	 *
 	 *Duplica la version obtenida junto con todas sus reglas y la guarda en otra tarifa. Este metodo sirve cuando una misma version con todas sus reglas aplica a mas de una tarifa.

Al duplicar una version, las reglas duplicadas con ella actualizan su id de la version a la nueva version creada.

Cuando una version activa y/o default se duplica, al guardarse en la otra tarifa pierde estas propiedades.
 	 *
 	 * @param id_tarifa int Id de la tarifa en la que se guardara la version con todas sus reglas
 	 * @param id_version int Id de la version a duplicar
 	 * @return id_version int Id de la version creada
 	 **/
  static function DuplicarVersion
	(
		$id_tarifa, 
		$id_version
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion basica de una version. El nombre, la fecha de inicio y la fecha de fin.

?Sera necesario permitir que el usuario cambie una version de una tarifa a otra tarifa?
 	 *
 	 * @param id_version int Id de la version a editar
 	 * @param fecha_fin string Fecha a aprtir de la cual se dejaran de aplicar las reglas de esta version. Si esta fecha ya paso se aplicaran las reglas de la version default de la tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si esta fecha aun no llega, se aplicaran las reglas de la version default de la tarifa.
 	 * @param nombre string Nombre de la version
 	 **/
  static function EditarVersion
	(
		$id_version, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina una version permamentemente de la base de datos junto a todas sus reglas.

La version default de la tarifa no puede ser eliminada ni la version activa.

La version por default de la tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_version int Id de la version a eliminar
 	 **/
  static function EliminarVersion
	(
		$id_version
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las versiones existentes, se puede filtrar por la tarifa y ordenar por los atributos de al tabla
 	 *
 	 * @param id_tarifa int Si este valor es obtenido, se listaran las versiones pertenecientes a esta tarifa
 	 * @param orden string nombre de al columna por la cual sera ordenada l alista
 	 * @return lista_versiones json Arreglo de versiones
 	 **/
  static function ListaVersion
	(
		$id_tarifa = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva version para una tarifa.

Si no se reciben fechas de inicio o fin, se da por hecho que la version no caduca. Si solo se recibe fecha de fin, se toma como la fecha de inicio la fecha actual del servidor. Si solo se recibe fecha de inicio, se toma como fecha final la maxima permitida por MySQL (9999-12-31 23:59:59).

La version por default de una tarifa no puede caducar.

Las tarifas solo pueden tener una version activa.
 	 *
 	 * @param id_tarifa int Id de la tarifa a la cual pertenece esta version
 	 * @param nombre string Nombre de la version
 	 * @param activa bool Determina si esta version sera la version activa para esta tarifa
 	 * @param default bool Si esta sera la version por default de esta tarifa. Una version por default no puede caducar
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las reglas de esta version. Cuando pase esta fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si aun no llega etsa fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @return id_version int Id de la version creada
 	 **/
  static function NuevaVersion
	(
		$id_tarifa, 
		$nombre, 
		$activa = null, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null
	);  
  
  
	
  
	/**
 	 *
 	 *Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

Una version default no puede caducar.
 	 *
 	 * @param id_version int Id de la version a la que se le dara el default
 	 **/
  static function SetDefaultVersion
	(
		$id_version
	);  
  
  
	
  }
