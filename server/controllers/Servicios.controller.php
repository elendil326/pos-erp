<?php
/**
  *
  *
  *
  **/
	
  class ServiciosController implements IServicios{
  
  
	/**
 	 *
 	 *Edita la informaci?e una clasificaci?e servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio que se edita
 	 * @param nombre string Nombre de la clasificacion de servicio
 	 * @param garantia int Numero de meses que tiene la garantia de este tipo de servicios
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param margen_utilidad float Margen de utilidad que tendran los servicios de este tipo de servicio
 	 * @param descuento float Descuento que aplicara a los servicios de esta clasificacion
 	 * @param impuestos json Impuestos que afectan a los servicios de esta clasificacion
 	 * @param retenciones json Retenciones que afectan a los servicios de esta clasificacion
 	 **/
	public function EditarClasificacion
	(
		$id_clasificacion_servicio, 
		$nombre, 
		$garantia = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una clasificacion de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio
 	 **/
	public function EliminarClasificacion
	(
		$id_clasificacion_servicio
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden del servicio a cancelar
 	 * @param motivo_cancelacion string Motivo de la cancelacion
 	 **/
	public function CancelarOrden
	(
		$id_orden_de_servicio, 
		$motivo_cancelacion = null
	)
	{  
  
  
	}
  
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
	public function NuevaClasificacion
	(
		$nombre, 
		$garantia = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$activa = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
  
  
	}
  
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
	public function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$activo = null, 
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crear un nuevo concepto de servicio.
 	 *
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param empresas json Objeto que contiene los ids de las empresas a las que pertenece este servicio
 	 * @param compra_en_mostrador bool Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param sucursales json Sucursales en las que estara disponible este servicio
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param retenciones json Ids de las retenciones que afectan este servicio
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param activo bool Si queremos que este activo o no mientras lo insertamos
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario   Array
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_servicio string La url de la foto del servicio
 	 * @return id_servicio int Id del servicio creado
 	 **/
	public function Nuevo
	(
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_servicio, 
		$codigo_servicio, 
		$empresas, 
		$compra_en_mostrador, 
		$sucursales, 
		$descripcion_servicio = null, 
		$garantia = null, 
		$retenciones = null, 
		$impuestos = null, 
		$activo = null, 
		$clasificaciones = null, 
		$margen_de_utilidad = null, 
		$control_de_existencia = null, 
		$foto_servicio = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita un servicio
 	 *
 	 * @param id_servicio int Id del servicio a editar
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param compra_en_mostrador string Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param empresas string Objeto que contiene los ids de las empresas a las que pertenece este servicio
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param sucursales json Sucursales en las cuales estara disponible este servicio
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param margen_de_utilidad string Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario Array
 	 * @param activo bool Si el servicio esta activo o no
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param retenciones json Ids de retenciones que afectan este servicio
 	 * @param foto_servicio string Url de la foto del servicio
 	 **/
	public function Editar
	(
		$id_servicio, 
		$costo_estandar, 
		$compra_en_mostrador, 
		$codigo_servicio, 
		$empresas, 
		$metodo_costeo, 
		$nombre_servicio, 
		$sucursales, 
		$control_de_existencia = null, 
		$margen_de_utilidad = null, 
		$clasificaciones = null, 
		$activo = null, 
		$descripcion_servicio = null, 
		$impuestos = null, 
		$garantia = null, 
		$retenciones = null, 
		$foto_servicio = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus ordenes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus ordenes
 	 * @param fecha_desde string Fecha en que se realizo la orden
 	 * @param fecha_hasta string fecha en que se entregara una orden
 	 * @return ordenes json Objeto que contendr� las ordenes.
 	 **/
	public function ListaOrden
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$fecha_desde = null, 
		$fecha_hasta = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Ver los detalles de una orden de servicio. Puede ordenarse por sus atributos. Los detalles de la orden de servicio son los seguimientos que tiene esa orden as?omo el estado y sus fechas de orden y de entrega.
 	 *
 	 * @param id_orden int Id de la orden a revisar
 	 * @return detalle_orden json Objeto que contendra el detalle de la orden
 	 **/
	public function DetalleOrden
	(
		$id_orden
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
 	 *
 	 * @param id_cliente int Id del cliente que contrata el servicio
 	 * @param id_servicio int Id del servicio que se contrata
 	 * @param fecha_entrega string Fecha en que se entregara el servicio.
 	 * @param descripcion string Descripcion de la orden o el porque del servicio
 	 * @param adelanto float Adelanto de la orden
 	 * @return id_orden int Id de la orden que se creo.
 	 **/
	public function NuevaOrden
	(
		$id_cliente, 
		$id_servicio, 
		$fecha_entrega, 
		$descripcion = null, 
		$adelanto = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
 	 *
 	 * @param estado string Estado en el que se encuentra actualmente la orden
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra actualmente la orden, se usara un -1 para indicar que esta en movimiento
 	 * @param id_orden_de_servicio int Id de la orden a darle seguimiento
 	 **/
	public function SeguimientoOrden
	(
		$estado, 
		$id_localizacion, 
		$id_orden_de_servicio
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Dar por terminada una orden, cuando el cliente satisface el ultimo pago
 	 *
 	 * @param id_orden int Id de la orden a terminar
 	 **/
	public function TerminarOrden
	(
		$id_orden
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Da de baja un servicio que ofrece una empresa
 	 *
 	 * @param id_servicio int Id del servicio que ser� eliminado
 	 **/
	public function Eliminar
	(
		$id_servicio
	)
	{  
  
  
	}
  }