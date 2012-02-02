<?php
/**
  *
  *
  *
  **/
	
  interface IProductos {
  
  
	/**
 	 *
 	 *Buscar productos
 	 *
 	 * @param query string 
 	 * @param id_sucursal int Buscar las existencias de este producto en una sucursal especifica.
 	 **/
  static function Buscar
	(
		$query, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
 	 *
 	 * @param id_categoria int Id de la categoria a desactivar
 	 **/
  static function DesactivarCategoria
	(
		$id_categoria
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo cambia la informacion de una categoria de producto
 	 *
 	 * @param id_categoria int Id de la categoria del producto
 	 * @param nombre string Nombre de la categoria del producto
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param garantia int Numero de meses de garantia con los que cuentan los productos de esta clasificacion
 	 * @param impuestos json Ids de impuestos que afectan a esta clasificacion de producto
 	 * @param retenciones json Ids de retenciones que afectan a esta clasificacion de producto
 	 **/
  static function EditarCategoria
	(
		$id_categoria, 
		$nombre, 
		$descripcion = null, 
		$garantia = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva categoria de producto, la categoria de un producto se relaciona con los meses de garantia del mismo, las unidades en las que se almacena entre, si se es suceptible a devoluciones, entre otros.
 	 *
 	 * @param nombre string Nombre de la categoria
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param garantia int Numero de meses de garantia con los que cuenta esta categoria de producto
 	 * @param impuestos json Ids de impuestos que afectan a esta categoria de producto
 	 * @param retenciones json Ids de retenciones que afectan esta clasificacion de productos
 	 * @return id_categoria int Id atogenerado por la insercion de la categoria
 	 **/
  static function NuevaCategoria
	(
		$nombre, 
		$descripcion = null, 
		$garantia = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo sirve para dar de baja un producto
 	 *
 	 * @param id_producto int Id del producto a desactivar
 	 **/
  static function Desactivar
	(
		$id_producto
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?n de un producto
 	 *
 	 * @param id_producto int Id del producto a editar
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuario
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param codigo_producto string Codigo del producto
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param empresas json arreglo de ids de empresas a las que pertenece este producto
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param garantia int Numero de meses de garantia de este producto
 	 * @param id_unidad int La unidad preferente de este producto
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param metodo_costeo string Puede ser "precio" o "costo" e indican si el precio final sera tomado a partir del costo del producto o del precio del mismo
 	 * @param nombre_producto string Nombre del producto
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param precio int El precio de este producto
 	 **/
  static function Editar
	(
		$id_producto, 
		$clasificaciones = null, 
		$codigo_de_barras = null, 
		$codigo_producto = null, 
		$compra_en_mostrador = null, 
		$control_de_existencia = null, 
		$costo_estandar = null, 
		$costo_extra_almacen = null, 
		$descripcion_producto = null, 
		$empresas = null, 
		$foto_del_producto = null, 
		$garantia = null, 
		$id_unidad = null, 
		$impuestos = null, 
		$metodo_costeo = null, 
		$nombre_producto = null, 
		$peso_producto = null, 
		$precio = null
	);  
  
  
	
  
	/**
 	 *
 	 *Se puede ordenar por los atributos de producto. 
 	 *
 	 * @param activo bool Si es true, mostrar solo los productos que estn activos, si es false mostrar solo los productos que no lo sean.
 	 * @param compra_en_mostrador bool True si se listaran los productos que se pueden comprar en mostrador, false si se listaran los que no se pueden comprar en mostrador
 	 * @param id_almacen int Id del almacen del cual se vern sus productos.
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos.
 	 * @param metodo_costeo string Se listaran los productos que coincidan con este metodo de costeo
 	 * @return productos json Objeto que contendrá el arreglo de productos en inventario.
 	 **/
  static function Lista
	(
		$activo = null, 
		$compra_en_mostrador = null, 
		$id_almacen = null, 
		$id_empresa = null, 
		$metodo_costeo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
 	 *
 	 * @param activo bool Si queremos que este activo o no este producto despues de crearlo.
 	 * @param codigo_producto string El codigo de control de la empresa para este producto, no se puede repetir
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param metodo_costeo string Puede ser "precio" o "costo" e indican si el precio final se tomara del precio de este producto o de su costo
 	 * @param nombre_producto string Nombre del producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param garantia int Si este producto cuenta con un nmero de meses de garanta  que no aplica a los productos de su categora
 	 * @param id_empresas json Arreglo que contendra los ids de las empresas a las que pertenece este producto
 	 * @param id_unidad int La unidad preferida para este producto
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param precio int El precio de este producto
 	 * @return id_producto int Id generado por la inserción del nuevo producto
 	 **/
  static function Nuevo
	(
		$activo, 
		$codigo_producto, 
		$compra_en_mostrador, 
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_producto, 
		$clasificaciones = null, 
		$codigo_de_barras = null, 
		$control_de_existencia = null, 
		$costo_extra_almacen = null, 
		$descripcion_producto = null, 
		$foto_del_producto = null, 
		$garantia = null, 
		$id_empresas = null, 
		$id_unidad = null, 
		$impuestos = null, 
		$peso_producto = null, 
		$precio = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agregar productos en volumen mediante un archivo CSV.
 	 *
 	 * @param productos json Arreglo de objetos que contendrán la información del nuevo producto
 	 * @return id_productos json Arreglo de enteros que contendrá los ids de los productos insertados.
 	 **/
  static function VolumenEnNuevo
	(
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo modifica la informacion de una unidad
 	 *
 	 * @param id_unidad string Id de la unidad a editar
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @param es_entero bool Si la unidad se manejara solo como enteros o con decimales
 	 * @param nombre string Nombre de la unidad convertible
 	 **/
  static function EditarUnidad
	(
		$id_unidad, 
		$descripcion = null, 
		$es_entero = null, 
		$nombre = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la equivalencia entre dos unidades.
1 kg = 2.204 lb
 	 *
 	 * @param equivalencia float La nueva equivalencia que se pondra entre los dos valores, en el ejemplo es 2.204
 	 * @param id_unidad int Id de la unidad, en el ejemplo son kilogramos
 	 * @param id_unidades int Id de la segunda unidad, en el ejemplo son libras
 	 **/
  static function EquivalenciaEditarUnidad
	(
		$equivalencia, 
		$id_unidad, 
		$id_unidades
	);  
  
  
	
  
	/**
 	 *
 	 *Descativa una unidad para que no sea usada por otro metodo
 	 *
 	 * @param id_unidad int Id de la unidad convertible a eliminar
 	 **/
  static function EliminarUnidad
	(
		$id_unidad
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina una equivalencia entre dos unidades.
Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param id_unidad int En el ejemplo es el kilogramo
 	 * @param id_unidades int En el ejemplo son las libras
 	 **/
  static function EquivalenciaEliminarUnidad
	(
		$id_unidad, 
		$id_unidades
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las unidades. Se puede filtrar por activas o inactivas y ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activas como inactivas, si es true, se listaran solo las activas, si es false se listaran solo las inactivas
 	 * @param ordenar string Nombre de la columna por la cual se ordenara la lista
 	 * @return unidades_convertibles json Lista de unidades convertibles
 	 **/
  static function ListaUnidad
	(
		$activo = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las equivalencias existentes. Se puede ordenar por sus atributos
 	 *
 	 * @param orden string Nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @return unidades_equivalencia json Lista de unidades
 	 **/
  static function EquivalenciaListaUnidad
	(
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo crea unidades, como son Kilogramos, Libras, Toneladas, Litros, costales, cajas, arpillas, etc.
 	 *
 	 * @param es_entero bool Boleano que indica si la unidad se manejara solo en enteros o con decimales
 	 * @param nombre string Nombre de la unidad convertible
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @return id_unidad_convertible string Id de la unidad convertible
 	 **/
  static function NuevaUnidad
	(
		$es_entero, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un registro de la equivalencia entre una unidad y otra. Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param equivalencia float Valor del coeficiente de la segunda unidad, es decir, las veces que cabe la segunda unidad en la primera
 	 * @param id_unidad int Id de la unidad. Esta unidad es tomada con coeficiente 1 en la ecuacion de, en el ejemplo es el kilogramo equivalencia
 	 * @param id_unidades int Id de la unidad equivalente, en el ejemplo es la libra
 	 **/
  static function EquivalenciaNuevaUnidad
	(
		$equivalencia, 
		$id_unidad, 
		$id_unidades
	);  
  
  
	
  }
