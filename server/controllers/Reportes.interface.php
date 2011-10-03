<?php
/**
  *
  *
  *
  **/

  interaface IReportes {
  
  
	/**
 	 *
 	 *Un usuario con grupo 1 o con el permiso puede generar reportes a la medida.
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
 	 *
 	 **/
	protected function Revisar_syntaxNuevo();  
  
  
  
  
	/**
 	 *
 	 *Obtener un detalle de un reporte
 	 *
 	 **/
	protected function Detalle();  
  
  
  
  
	/**
 	 *
 	 *Lista todos los reportes, pueden filtrarse por empresa, por sucursal, y ordenarse por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
 	 *
 	 **/
	protected function ProductosCliente();  
  
  
  
  
	/**
 	 *
 	 *Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
 	 *
 	 **/
	protected function Servicio_cliente();  
  
  
  
  }
