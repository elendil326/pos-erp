<?php
/**
  *
  *
  *
  **/
	
  interface IProductos {
  
  
	/**
 	 *
 	 *Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
 	 *
 	 * @param id_categoria int Id de la categoria a desactivar
 	 **/
  function DesactivarCategoria
	(
		$id_categoria
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo cambia la informacion de una categoria de producto
 	 *
 	 * @param id_categoria int Id de la categoria del producto
 	 * @param nombre string Nombre de la categoria del producto
 	 * @param garantia int Numero de meses de garantia con los que cuentan los productos de esta clasificacion
 	 * @param descuento float Descuento que tendran los productos de esta categoria
 	 * @param margen_utilidad float Margen de utilidad de los productos que formen parte de esta categoria
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param impuestos json Ids de impuestos que afectan a esta clasificacion de producto
 	 * @param retenciones json Ids de retenciones que afectan a esta clasificacion de producto
 	 **/
  function EditarCategoria
	(
		$id_categoria, 
		$nombre, 
		$garantia = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crea una nueva categoria de producto, la categoria de un producto se relaciona con los meses de garantia del mismo, las unidades en las que se almacena entre, si se es suceptible a devoluciones, entre otros.
 	 *
 	 * @param nombre string Nombre de la categoria
 	 * @param descripcion string Descripcion larga de la categoria
 	 * @param garantía int Numero de meses de garantia con los que cuenta esta categoria de producto
 	 * @param margen_utilidad float Margen de utilidad que tendran los productos de esta categoria
 	 * @param descuento float Descuento que tendran los productos de esta categoria
 	 * @param impuestos json Ids de impuestos que afectan a esta categoria de producto
 	 * @param retenciones json Ids de retenciones que afectan esta clasificacion de productos
 	 * @return id_categoria int Id atogenerado por la insercion de la categoria
 	 **/
  function NuevaCategoria
	(
		$nombre, 
		$descripcion = null, 
		$garantía = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo sirve para dar de baja un producto
 	 *
 	 * @param id_producto int Id del producto a desactivar
 	 **/
  function Desactivar
	(
		$id_producto
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?e un producto
 	 *
 	 * @param codigo_producto string Codigo del producto
 	 * @param id_producto int Id del producto a editar
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param nombre_producto string Nombre del producto
 	 * @param empresas json arreglo de empresas a las que pertenece este producto
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param activo bool Si queremos que este activo o no.
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param garantía int Si este producto cuenta con un nmero de meses de garantia que no aplican a los demas productos de su categoria
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este producto marque utilidad en especifico
 	 * @param id_unidad_no_convertible int Si este producto se relacionara con una unidad no convertible ( lotes, cajas, costales, etc.)
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param id_unidad_convertible int Si este producto se relacionara con una unidad convertible (kilos, libras, litros, etc.) 
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param descuento float Descuento que tendra este producot
 	 **/
  function Editar
	(
		$codigo_producto, 
		$id_producto, 
		$costo_estandar, 
		$nombre_producto, 
		$empresas, 
		$compra_en_mostrador, 
		$activo, 
		$metodo_costeo, 
		$control_de_existencia = null, 
		$costo_extra_almacen = null, 
		$peso_producto = null, 
		$codigo_de_barras = null, 
		$garantía = null, 
		$margen_de_utilidad = null, 
		$id_unidad_no_convertible = null, 
		$impuestos = null, 
		$clasificaciones = null, 
		$id_unidad_convertible = null, 
		$descripcion_producto = null, 
		$foto_del_producto = null, 
		$descuento = null
	);  
  
  
	
  
	/**
 	 *
 	 *Se puede ordenar por los atributos de producto. 
 	 *
 	 * @param activo bool Si es true, mostrar solo los productos que estn activos, si es false mostrar solo los productos que no lo sean.
 	 * @param id_lote int Id del lote del cual se vern sus productos.
 	 * @param id_almacen int Id del almacen del cual se vern sus productos.
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos.
 	 * @param id_sucursal int Id de la sucursal de la cual se vern los productos.
 	 * @return productos json Objeto que contendrá el arreglo de productos en inventario.
 	 **/
  function Lista
	(
		$activo = null, 
		$id_lote = null, 
		$id_almacen = null, 
		$id_empresa = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo producto, 

NOTA: Se crea un producto tipo = 1 que es para productos
 	 *
 	 * @param activo bool Si queremos que este activo o no este producto despues de crearlo.
 	 * @param codigo_producto string El codigo de control de la empresa para este producto, no se puede repetir
 	 * @param id_empresas json Objeto que contendra el arreglo de ids de las empresas a la que pertenece este producto
 	 * @param nombre_producto string Nombre del producto
 	 * @param metodo_costeo string  Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param garantía int Si este producto cuenta con un nmero de meses de garanta  que no aplica a los productos de su categora
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este producto marque utilidad en especifico
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param id_unidad_convertible int Si este producto se relacionara con una unidad convertible ( kilos, litros, libras, etc.)
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param id_unidad_no_convertible int Si este producto se relacionara con una unidad no convertible ( lotes, cajas, costales, etc.)
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param descuento float Descuento que se aplicara a este producot
 	 * @return id_producto int Id generado por la inserción del nuevo producto
 	 **/
  function Nuevo
	(
		$activo, 
		$codigo_producto, 
		$id_empresas, 
		$nombre_producto, 
		$metodo_costeo, 
		$costo_estandar, 
		$compra_en_mostrador, 
		$garantía = null, 
		$costo_extra_almacen = null, 
		$margen_de_utilidad = null, 
		$control_de_existencia = null, 
		$peso_producto = null, 
		$descripcion_producto = null, 
		$impuestos = null, 
		$clasificaciones = null, 
		$id_unidad_convertible = null, 
		$codigo_de_barras = null, 
		$id_unidad_no_convertible = null, 
		$foto_del_producto = null, 
		$descuento = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agregar productos en volumen mediante un archivo CSV.
 	 *
 	 * @param productos json Arreglo de objetos que contendrán la información del nuevo producto
 	 * @return id_productos json Arreglo de enteros que contendrá los ids de los productos insertados.
 	 **/
  function En_volumenNuevo
	(
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo modifica la informacion de una unidad convertible
 	 *
 	 * @param id_unidad_convertible string Id de la unidad convertible a editar
 	 * @param nombre string Nombre de la unidad convertible
 	 * @param descripcion string Descripcion de la unidad convertible
 	 **/
  function EditarUnidad_convertible
	(
		$id_unidad_convertible, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Descativa una unidad convertible para que no sea usada por otro metodo
 	 *
 	 * @param id_unidad_convertible int Id de la unidad convertible a eliminar
 	 **/
  function EliminarUnidad_convertible
	(
		$id_unidad_convertible
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las unidades convertibles. Se puede filtrar por activas o inactivas y ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activas como inactivas, si es true, se listaran solo las activas, si es false se listaran solo las inactivas
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return unidades_convertibles json Lista de unidades convertibles
 	 **/
  function ListaUnidad_convertible
	(
		$activo = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo crea unidades convertibles, como son Kilogramos, Libras, Toneladas, Litros, etc.
 	 *
 	 * @param nombre string Nombre de la unidad convertible
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @return id_unidad_convertible string Id de la unidad convertible
 	 **/
  function NuevaUnidad_convertible
	(
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Metodo que cambia la informacion de una unidad no convertible
 	 *
 	 * @param id_unidad_no_convertible int Id de la unidad no convertible a editar
 	 * @param nombre string Nombre de la unidad no convertible
 	 * @param descripcion string Descripcion larga de la unidad no convertible
 	 **/
  function EditarUnidad_no_convertible
	(
		$id_unidad_no_convertible, 
		$nombre, 
		$descripcion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva una unidad no convertible para que no sea usada por otro producto
 	 *
 	 * @param id_unidad_no_convertible int Id de la unidad no convertible a eliminar
 	 **/
  function EliminarUnidad_no_convertible
	(
		$id_unidad_no_convertible
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las unidades no convertibles. Se puede filtrar por activas e inactivas y ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran unidades tanto activas como inactivas. Si es true, se listaran solo las activas, si es false se listaran las inactivas
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return unidades_no_convertibles json lista de unidades no convertibles
 	 **/
  function ListaUnidad_no_convertible
	(
		$activo = null, 
		$ordenar = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo crea una nueva unidad no convertible (caja, lote, arpilla, costal, etc.) Las unidades no convertibles son aquellas que var? su valor y su peso de acuerdo al producto que se ingresa en ellas.
 	 *
 	 * @param nombre string Nombre de la unidad no convertible
 	 * @param descripcion string Descripcion larga de la unidad no convertible
 	 * @return id_unidad_no_convertible int Id autogenerado por la insercion de la unidad no convertible
 	 **/
  function NuevaUnidad_no_convertible
	(
		$nombre, 
		$descripcion = null
	);  
  
  
	
  }
