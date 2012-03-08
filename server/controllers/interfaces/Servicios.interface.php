<?php
/**
  *
  *
  *
  **/
	
  interface IServicios {
  
  
	/**
 	 *
 	 *Lista todos los servicios de la instancia. Puede filtrarse por empresa, por sucursal o por activo y puede ordenarse por sus atributos.
 	 *
 	 * @param activo bool Si este valor no es obtenido, se mostraran los servicios tanto activos como inactivos. Si es true, se mostraran solo los activos, si es false se mostraran solo los inactivos.
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus servicios
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus servicios
 	 * @return servicios json Objeto que contendra la lista de servicios
 	 **/
  static function Buscar
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informaci?n de una clasificaci?n de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio que se edita
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param garantia int Numero de meses que tiene la garantia de este tipo de servicios
 	 * @param impuestos json Impuestos que afectan a los servicios de esta clasificacion
 	 * @param nombre string Nombre de la clasificacion de servicio
 	 * @param retenciones json Retenciones que afectan a los servicios de esta clasificacion
 	 **/
  static function EditarClasificacion
	(
		$id_clasificacion_servicio, 
		$descripcion = null, 
		$garantia = null, 
		$impuestos = null, 
		$nombre = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina una clasificacion de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio
 	 **/
  static function EliminarClasificacion
	(
		$id_clasificacion_servicio
	);  
  
  
	
  
	/**
 	 *
 	 *Genera una nueva clasificacion de servicio
 	 *
 	 * @param nombre string Nombre de la clasificacion del servicio
 	 * @param activa bool Si esta clasificacion sera activa al momento de ser creada
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param garantia int numero de meses de garantia que tienen los servicios de esta clasificacion
 	 * @param impuestos json Impuestos que afectan a este tipo de servicio
 	 * @param retenciones json Retenciones que afectana este tipo de servicio
 	 * @return id_clasificacion_servicio int Id de la clasificacion que se creo
 	 **/
  static function NuevaClasificacion
	(
		$nombre, 
		$activa =  1 , 
		$descripcion = null, 
		$garantia = null, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita un servicio
 	 *
 	 * @param id_servicio int Id del servicio a editar
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario Array
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param compra_en_mostrador string Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param empresas string Arreglo de ids de empresas a las que pertenecera este servicio
 	 * @param foto_servicio string Url de la foto del servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param metodo_costeo string Puede ser "precio" o "costo" e indica si el precio final sera tomado del precio base del servicio o de su costo
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param precio float Precio del servicio
 	 * @param retenciones json Ids de retenciones que afectan este servicio
 	 * @param sucursales json Arreglo de ids sucursales a las que pertencera este servicio
 	 **/
  static function Editar
	(
		$id_servicio, 
		$clasificaciones = null, 
		$codigo_servicio = null, 
		$compra_en_mostrador = null, 
		$control_de_existencia = null, 
		$costo_estandar = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$foto_servicio = null, 
		$garantia = null, 
		$impuestos = null, 
		$metodo_costeo = null, 
		$nombre_servicio = null, 
		$precio = null, 
		$retenciones = null, 
		$sucursales = null
	);  
  
  
	
  
	/**
 	 *
 	 *Da de baja un servicio que ofrece una empresa
 	 *
 	 * @param id_servicio int Id del servicio que será eliminado
 	 **/
  static function Eliminar
	(
		$id_servicio
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo concepto de servicio.
 	 *
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param compra_en_mostrador bool Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param metodo_costeo string Puede ser "precio" o "costo" e indica si el precio final sera tomado en base al precio base del servicio o a su costo
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param activo bool Si queremos que este activo o no mientras lo insertamos
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario   Array
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param empresas json Arreglo de ids de empresas a las que pertenecera este servicio
 	 * @param foto_servicio string La url de la foto del servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param precio float Precio del servicio
 	 * @param retenciones json Ids de las retenciones que afectan este servicio
 	 * @param sucursales json Arreglo de ids de sucursales a las que pertenecera este servicio
 	 * @return id_servicio int Id del servicio creado
 	 **/
  static function Nuevo
	(
		$codigo_servicio, 
		$compra_en_mostrador, 
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_servicio, 
		$activo =  true , 
		$clasificaciones = null, 
		$control_de_existencia = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$foto_servicio = null, 
		$garantia = null, 
		$impuestos = null, 
		$precio = null, 
		$retenciones = null, 
		$sucursales = null
	);  
  
  
	
  
	/**
 	 *
 	 *En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio. Este metodo puede ser usado apra agregar mas cantidad de un producto a uno ya existente, en este caso se ignoran los campos impuesto, descuento y retencion del arreglo de productos.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio a la cual se le agregaran los productos
 	 * @param productos json Arreglo de objetos con ids de producto, de unidad, sus cantidades, su precio, su impuesto, retencion y descuento.
 	 **/
  static function ProductosAgregarOrden
	(
		$id_orden_de_servicio, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden del servicio a cancelar
 	 * @param motivo_cancelacion string Motivo de la cancelacion
 	 **/
  static function CancelarOrden
	(
		$id_orden_de_servicio, 
		$motivo_cancelacion = null
	);  
  
  
	
  
	/**
 	 *
 	 *Ver los detalles de una orden de servicio. Puede ordenarse por sus atributos. Los detalles de la orden de servicio son los seguimientos que tiene esa orden as? como el estado y sus fechas de orden y de entrega.
 	 *
 	 * @param id_orden int Id de la orden a revisar
 	 * @return seguimientos json Arreglo con objetos Seguimiento
 	 **/
  static function DetalleOrden
	(
		$id_orden
	);  
  
  
	
  
	/**
 	 *
 	 *Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
 	 *
 	 * @param activa bool Se listaran las ordenes con el valor de activo obtenido
 	 * @param cancelada bool Se listaran las ordenes con valir de cancelada obtenido
 	 * @param fecha_desde int Fecha en que se realizo la orden
 	 * @param fecha_hasta int fecha en que se entregara una orden
 	 * @param id_servicio int Se listaran las ordenes que contengan este servicio
 	 * @param id_usuario_venta int Se listaran las ordenes de este usuario
 	 * @return numero_de_resultados int Objeto que contendr las ordenes.
 	 * @return resultados json 
 	 **/
  static function ListaOrden
	(
		$activa = null, 
		$cancelada = null, 
		$fecha_desde = null, 
		$fecha_hasta = null, 
		$id_servicio = null, 
		$id_usuario_venta = null
	);  
  
  
	
  
	/**
 	 *
 	 *Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
 	 *
 	 * @param id_cliente int Id del cliente que contrata el servicio
 	 * @param id_servicio int Id del servicio que se contrata
 	 * @param adelanto float Adelanto de la orden
 	 * @param cliente_reporta string lo que el cliente reporta hacerca de ...
 	 * @param condiciones_de_recepcion string condiciones fisica en las que se recibe el equipo
 	 * @param descripcion string Descripcion de la orden o el porque del servicio
 	 * @param fecha_entrega int Fecha en que se entregara el servicio. En caso de aplicar. Unix Time Stamp
 	 * @param fotografia string 
 	 * @param precio float en caso de que el metodo de costeo sea variable, hay que mandar un precio final
 	 * @return id_orden int Id de la orden que se creo.
 	 * @return id_venta string `id_venta` de la venta que genero este servicio
 	 **/
  static function NuevaOrden
	(
		$id_cliente, 
		$id_servicio, 
		$adelanto = null, 
		$cliente_reporta = null, 
		$condiciones_de_recepcion = null, 
		$descripcion = "", 
		$fecha_entrega = "", 
		$fotografia = null, 
		$precio = null
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo se usa para quitar productos de una orden de servicio. Puede ser usado para reducir su cantidad o para retirarlo por completo
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio de la cual se moveran los productos
 	 * @param productos json Arreglo que contendra los ids de productos, de unidades y  sus cantidades a retirar
 	 **/
  static function ProductosQuitarOrden
	(
		$id_orden_de_servicio, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden a darle seguimiento
 	 * @param abono json {cantidad : "int",metodo : "efectivo"}
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra actualmente la orden. Si se omite entonces el seguimiento se tomara que esta en la misma sucursal del ultimo servicio o bien de donde se inicio el servicio.
 	 * @param nota string Nota por la cual se inicio el seguimiento, es una cadena describiendo la causa del seguimiento.
 	 **/
  static function SeguimientoOrden
	(
		$id_orden_de_servicio, 
		$abono = null, 
		$id_localizacion = null, 
		$nota = null
	);  
  
  
	
  
	/**
 	 *
 	 *Dar por terminada una orden, al momento de terminarse una orden se genera una venta, por lo tanto, al terminar la orden hay que especificar datos de la misma.
 	 *
 	 * @param id_orden int Id de la orden a terminar
 	 * @param tipo_venta string Si la venta que se va a generar sera a credito o de contado
 	 * @param billetes_cambio json Objeto que contendra la informacion de billetes que se daran como cambio
 	 * @param billetes_pago json Objeto que contendra la informacion de los billetes que se usan para pagar
 	 * @param cheques json Objeto que contendra la informacion de los cheques que se usan para pagar
 	 * @param descuento float El monto a descontar de la venta si habra un descuento.
 	 * @param id_venta_caja int Id de la venta de la caja en caso de q se haya ido el internet
 	 * @param saldo float Si se saldara una parte de la venta en caso de ser a credito
 	 * @param tipo_de_pago string Si el tipo de pago sera en cheque, tarjeta o en efectivo.
 	 **/
  static function TerminarOrden
	(
		$id_orden, 
		$tipo_venta, 
		$billetes_cambio = null, 
		$billetes_pago = null, 
		$cheques = null, 
		$descuento = null, 
		$id_venta_caja = null, 
		$saldo = null, 
		$tipo_de_pago = null
	);  
  
  
	
  }
