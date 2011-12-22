<?php
/**
  *
  *
  *
  **/
	
  interface IServicios {
  
  
	/**
 	 *
 	 *Edita la informaci?n de una clasificaci?n de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio que se edita
 	 * @param retenciones json Retenciones que afectan a los servicios de esta clasificacion
 	 * @param impuestos json Impuestos que afectan a los servicios de esta clasificacion
 	 * @param descuento float Descuento que aplicara a los servicios de esta clasificacion
 	 * @param margen_utilidad float Margen de utilidad que tendran los servicios de este tipo de servicio
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param garantia int Numero de meses que tiene la garantia de este tipo de servicios
 	 * @param nombre string Nombre de la clasificacion de servicio
 	 **/
  static function EditarClasificacion
	(
		$id_clasificacion_servicio, 
		$retenciones = null, 
		$impuestos = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$garantia = null, 
		$nombre = null
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
 	 * @param garantia int numero de meses de garantia que tienen los servicios de esta clasificacion
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a los servicios de este tipo
 	 * @param descuento float Descuento que aplicara a los servicios de este tipo
 	 * @param activa bool Si esta clasificacion sera activa al momento de ser creada
 	 * @param impuestos json Impuestos que afectan a este tipo de servicio
 	 * @param retenciones json Retenciones que afectana este tipo de servicio
 	 * @return id_clasificacion_servicio int Id de la clasificacion que se creo
 	 **/
  static function NuevaClasificacion
	(
		$nombre, 
		$garantia = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$activa = 1, 
		$impuestos = null, 
		$retenciones = null
	);  
  
  
	
  
	/**
 	 *
 	 *Edita un servicio
 	 *
 	 * @param id_servicio int Id del servicio a editar
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param retenciones json Ids de retenciones que afectan este servicio
 	 * @param sucursales json Sucursales en las cuales estara disponible este servicio
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario Array
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param empresas string Objeto que contiene los ids de las empresas a las que pertenece este servicio
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param compra_en_mostrador string Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_servicio string Url de la foto del servicio
 	 * @param margen_de_utilidad string Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @param precio float Precio del servicio
 	 **/
  static function Editar
	(
		$id_servicio, 
		$costo_estandar = null, 
		$retenciones = null, 
		$sucursales = null, 
		$clasificaciones = null, 
		$garantia = null, 
		$impuestos = null, 
		$nombre_servicio = null, 
		$metodo_costeo = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$codigo_servicio = null, 
		$compra_en_mostrador = null, 
		$control_de_existencia = null, 
		$foto_servicio = null, 
		$margen_de_utilidad = null, 
		$precio = null
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
 	 *Lista todos los servicios de la instancia. Puede filtrarse por empresa, por sucursal o por activo y puede ordenarse por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus servicios
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus servicios
 	 * @param activo bool Si este valor no es obtenido, se mostraran los servicios tanto activos como inactivos. Si es true, se mostraran solo los activos, si es false se mostraran solo los inactivos.
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return servicios json Objeto que contendra la lista de servicios
 	 **/
  static function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$activo = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Crear un nuevo concepto de servicio.
 	 *
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param metodo_costeo string precio o margen
 	 * @param compra_en_mostrador bool Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param precio float Precio del servicio
 	 * @param retenciones json Ids de las retenciones que afectan este servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param foto_servicio string La url de la foto del servicio
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param activo bool Si queremos que este activo o no mientras lo insertamos
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario   Array
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @return id_servicio int Id del servicio creado
 	 **/
  static function Nuevo
	(
		$codigo_servicio, 
		$metodo_costeo, 
		$compra_en_mostrador, 
		$costo_estandar, 
		$nombre_servicio, 
		$precio = null, 
		$retenciones = null, 
		$garantia = null, 
		$foto_servicio = null, 
		$control_de_existencia = null, 
		$descripcion_servicio = null, 
		$activo = true, 
		$clasificaciones = null, 
		$impuestos = null, 
		$margen_de_utilidad = null
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
 	 * @return detalle_orden json Objeto que contendra el detalle de la orden
 	 **/
  static function DetalleOrden
	(
		$id_orden
	);  
  
  
	
  
	/**
 	 *
 	 *Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
 	 *
 	 * @param id_servicio int Se listaran las ordenes que contengan este servicio
 	 * @param fecha_hasta string fecha en que se entregara una orden
 	 * @param fecha_desde string Fecha en que se realizo la orden
 	 * @param id_usuario_venta int Se listaran las ordenes de este usuario
 	 * @param activa bool Se listaran las ordenes con el valor de activo obtenido
 	 * @param cancelada bool Se listaran las ordenes con valir de cancelada obtenido
 	 * @return ordenes json Objeto que contendrá las ordenes.
 	 **/
  static function ListaOrden
	(
		$id_servicio = null, 
		$fecha_hasta = null, 
		$fecha_desde = null, 
		$id_usuario_venta = null, 
		$activa = null, 
		$cancelada = null
	);  
  
  
	
  
	/**
 	 *
 	 *Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
 	 *
 	 * @param descripcion string Descripcion de la orden o el porque del servicio
 	 * @param id_servicio int Id del servicio que se contrata
 	 * @param id_cliente int Id del cliente que contrata el servicio
 	 * @param fecha_entrega string Fecha en que se entregara el servicio.
 	 * @param adelanto float Adelanto de la orden
 	 * @return id_orden int Id de la orden que se creo.
 	 **/
  static function NuevaOrden
	(
		$descripcion, 
		$id_servicio, 
		$id_cliente, 
		$fecha_entrega, 
		$adelanto = null
	);  
  
  
	
  
	/**
 	 *
 	 *Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
 	 *
 	 * @param estado string Estado en el que se encuentra actualmente la orden
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra actualmente la orden, se usara un -1 para indicar que esta en movimiento
 	 * @param id_orden_de_servicio int Id de la orden a darle seguimiento
 	 **/
  static function SeguimientoOrden
	(
		$estado, 
		$id_localizacion, 
		$id_orden_de_servicio
	);  
  
  
	
  
	/**
 	 *
 	 *Dar por terminada una orden, al momento de terminarse una orden se genera una venta, por lo tanto, al terminar la orden hay que especificar datos de la misma.
 	 *
 	 * @param id_orden int Id de la orden a terminar
 	 * @param tipo_venta string Si la venta que se va a generar sera a credito o de contado
 	 * @param descuento float El monto a descontar de la venta si habra un descuento.
 	 * @param saldo float Si se saldara una parte de la venta en caso de ser a credito
 	 * @param tipo_de_pago string Si el tipo de pago sera en cheque, tarjeta o en efectivo.
 	 **/
  static function TerminarOrden
	(
		$id_orden, 
		$tipo_venta, 
		$descuento = null, 
		$saldo = null, 
		$tipo_de_pago = null
	);  
  
  
	
  }
