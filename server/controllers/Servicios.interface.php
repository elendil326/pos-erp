<?php
/**
  *
  *
  *
  **/

  interaface IServicios {
  
  
	/**
 	 *
 	 *Edita la información de una clasificación de servicio
 	 *
 	 **/
	protected function EditarClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Elimina una clasificacion de servicio
 	 *
 	 **/
	protected function EliminarClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
 	 *
 	 **/
	protected function CancelarOrden();  
  
  
  
  
	/**
 	 *
 	 *Genera una nueva clasificacion de servicio
 	 *
 	 **/
	protected function NuevaClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Lista todos los servicios de la instancia. Puede filtrarse por empresa, por sucursal o por activo y puede ordenarse por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Crear un nuevo concepto de servicio.
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Edita un servicio
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
 	 *
 	 **/
	protected function ListaOrden();  
  
  
  
  
	/**
 	 *
 	 *Ver los detalles de una orden de servicio. Puede ordenarse por sus atributos. Los detalles de la orden de servicio son los seguimientos que tiene esa orden así como el estado y sus fechas de orden y de entrega.
 	 *
 	 **/
	protected function DetalleOrden();  
  
  
  
  
	/**
 	 *
 	 *Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
 	 *
 	 **/
	protected function NuevaOrden();  
  
  
  
  
	/**
 	 *
 	 *Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
 	 *
 	 **/
	protected function SeguimientoOrden();  
  
  
  
  
	/**
 	 *
 	 *Dar por terminada una orden, cuando el cliente satisface el ultimo pago
 	 *
 	 **/
	protected function TerminarOrden();  
  
  
  
  
	/**
 	 *
 	 *Da de baja un servicio que ofrece una empresa
 	 *
 	 **/
	protected function Eliminar();  
  
  
  
  }
