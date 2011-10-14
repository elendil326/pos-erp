<?php
require_once("Productos.interface.php");
/**
  *
  *
  *
  **/
	
  class ProductosController implements IProductos{
  
  
	/**
 	 *
 	 *Lista las unidades. Se puede filtrar por activas o inactivas y ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activas como inactivas, si es true, se listaran solo las activas, si es false se listaran solo las inactivas
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @return unidades_convertibles json Lista de unidades convertibles
 	 **/
	public function ListaUnidad
	(
		$activo = null, 
		$ordenar = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea un registro de la equivalencia entre una unidad y otra. Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param id_unidad int Id de la unidad. Esta unidad es tomada con coeficiente 1 en la ecuacion de, en el ejemplo es el kilogramo equivalencia
 	 * @param id_unidades int Id de la unidad equivalente, en el ejemplo es la libra
 	 * @param equivalencia float Valor del coeficiente de la segunda unidad, es decir, las veces que cabe la segunda unidad en la primera
 	 **/
	public function Nueva_equivalenciaUnidad
	(
		$id_unidad, 
		$id_unidades, 
		$equivalencia
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la equivalencia entre dos unidades.
1 kg = 2.204 lb
 	 *
 	 * @param id_unidades int Id de la segunda unidad, en el ejemplo son libras
 	 * @param equivalencia float La nueva equivalencia que se pondra entre los dos valores, en el ejemplo es 2.204
 	 * @param id_unidad int Id de la unidad, en el ejemplo son kilogramos
 	 **/
	public function Editar_equivalenciaUnidad
	(
		$id_unidades, 
		$equivalencia, 
		$id_unidad
	)
	{  
  
  
	}
  
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
	public function Lista
	(
		$activo = null, 
		$id_lote = null, 
		$id_almacen = null, 
		$id_empresa = null, 
		$id_sucursal = null
	)
	{  
  
  
	}
  
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
	public function Nuevo
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
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Agregar productos en volumen mediante un archivo CSV.
 	 *
 	 * @param productos json Arreglo de objetos que contendrán la información del nuevo producto
 	 * @return id_productos json Arreglo de enteros que contendrá los ids de los productos insertados.
 	 **/
	public function En_volumenNuevo
	(
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo sirve para dar de baja un producto
 	 *
 	 * @param id_producto int Id del producto a desactivar
 	 **/
	public function Desactivar
	(
		$id_producto
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informaci?e un producto
 	 *
 	 * @param id_producto int Id del producto a editar
 	 * @param descuento float Descuento que tendra este producot
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param descripcion_producto string Descripcion larga del producto
 	 * @param id_unidad_no_convertible int Si este producto se relacionara con una unidad no convertible ( lotes, cajas, costales, etc.)
 	 * @param impuestos json array de ids de impuestos que tiene este producto
 	 * @param clasificaciones json Uno o varios id_clasificacion de este producto, esta clasificacion esta dada por el usuarioArray
 	 * @param id_unidad_convertible int Si este producto se relacionara con una unidad convertible (kilos, libras, litros, etc.) 
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este producto marque utilidad en especifico
 	 * @param garantía int Si este producto cuenta con un nmero de meses de garantia que no aplican a los demas productos de su categoria
 	 * @param compra_en_mostrador bool Verdadero si este producto se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param codigo_de_barras string El Codigo de barras para este producto
 	 * @param empresas json arreglo de empresas a las que pertenece este producto
 	 * @param peso_producto float el peso de este producto en KG
 	 * @param costo_estandar float Valor del costo estndar del producto.
 	 * @param nombre_producto string Nombre del producto
 	 * @param costo_extra_almacen float Si este producto produce un costo extra por tenerlo en almacen
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_del_producto string url a una foto de este producto
 	 * @param codigo_producto string Codigo del producto
 	 **/
	public function Editar
	(
		$id_producto, 
		$descuento = null, 
		$metodo_costeo = null, 
		$descripcion_producto = null, 
		$id_unidad_no_convertible = null, 
		$impuestos = null, 
		$clasificaciones = null, 
		$id_unidad_convertible = null, 
		$margen_de_utilidad = null, 
		$garantía = null, 
		$compra_en_mostrador = null, 
		$codigo_de_barras = null, 
		$empresas = null, 
		$peso_producto = null, 
		$costo_estandar = null, 
		$nombre_producto = null, 
		$costo_extra_almacen = null, 
		$control_de_existencia = null, 
		$foto_del_producto = null, 
		$codigo_producto = null
	)
	{  
  
  
	}
  
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
	public function NuevaCategoria
	(
		$nombre, 
		$descripcion = null, 
		$garantía = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
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
	public function EditarCategoria
	(
		$id_categoria, 
		$nombre, 
		$garantia = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo desactiva una categoria de tal forma que ya no se vuelva a usar como categoria sobre un producto.
 	 *
 	 * @param id_categoria int Id de la categoria a desactivar
 	 **/
	public function DesactivarCategoria
	(
		$id_categoria
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una equivalencia entre dos unidades.
Ejemplo: 1 kg = 2.204 lb
 	 *
 	 * @param id_unidades int En el ejemplo son las libras
 	 * @param id_unidad int En el ejemplo es el kilogramo
 	 **/
	public function Eliminar_equivalenciaUnidad
	(
		$id_unidades, 
		$id_unidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las equivalencias existentes. Se puede ordenar por sus atributos
 	 *
 	 * @param orden string Nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @return unidades_equivalencia json Lista de unidades
 	 **/
	public function Lista_equivalenciaUnidad
	(
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo crea unidades, como son Kilogramos, Libras, Toneladas, Litros, costales, cajas, arpillas, etc.
 	 *
 	 * @param nombre string Nombre de la unidad convertible
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @return id_unidad_convertible string Id de la unidad convertible
 	 **/
	public function NuevaUnidad
	(
		$nombre, 
		$descripcion = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Este metodo modifica la informacion de una unidad
 	 *
 	 * @param id_unidad_convertible string Id de la unidad convertible a editar
 	 * @param descripcion string Descripcion de la unidad convertible
 	 * @param nombre string Nombre de la unidad convertible
 	 **/
	public function EditarUnidad
	(
		$id_unidad_convertible, 
		$descripcion = null, 
		$nombre = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Descativa una unidad para que no sea usada por otro metodo
 	 *
 	 * @param id_unidad_convertible int Id de la unidad convertible a eliminar
 	 **/
	public function EliminarUnidad
	(
		$id_unidad_convertible
	)
	{  
  
  
	}
  }
