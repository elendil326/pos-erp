<?php
/**
  *
  *
  *
  **/
	
  interface IReportes {
  
  
	/**
 	 *
 	 *Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
 	 *
 	 * @param id_cliente int Id del cliente del cual se listaran sus productos.
 	 * @param id_empresa int Id empresa de la cual se listaran los productos comprados por ese cliente
 	 * @param id_sucursal int Id sucursal de la cual se listaran los productos comprados por ese cliente
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return Lista_productos_cliente json Objeto que contendrá la información de la lista.
 	 **/
  static function ProductosCliente
	(
		$id_cliente = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Obtener un detalle de un reporte
 	 *
 	 * @param id_reporte int Id del reporte a revisar.
 	 * @return detalle_reporte json Objeto que contendrá la información del detalle.
 	 **/
  static function Detalle
	(
		$id_reporte
	);  
  
  
	
  
	/**
 	 *
 	 *Lista todos los reportes, pueden filtrarse por empresa, por sucursal, y ordenarse por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus reportes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus reportes
 	 * @param orden string Nombre de la columan por el cual se ordenara la lista
 	 * @return reportes json Objeto que contendra la lista de reportes
 	 **/
  static function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  
	/**
 	 *
 	 *Un usuario con grupo 1 o con el permiso puede generar reportes a la medida.
 	 *
 	 * @return id_reporte int Id del reporte generado
 	 **/
  static function Nuevo
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Revisar la syntaxis de un JSON que resulta de crear un nuevo reporte.
 	 *
 	 * @param nuevo_reporte json JSON a analizar
 	 **/
  static function Revisar_syntaxNuevo
	(
		$nuevo_reporte
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra una lista de los servicios que ha comprado el cliente con su cantidad, puede ordenarse por cantidad.Puede filtrarse por un cliente especifico, por la sucursal en la que compro, la empresa en la que compro.
 	 *
 	 * @param id_cliente int Id del cliente del cual se listaran sus productos.
 	 * @param id_empresa int Id de la empresa de la cual se listaran los servicios que han comprado los clientes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran los Servicios que han comprado los clientes
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return Lista_servicios_cliente json Objeto que contendra la lista de servicios y clientes que los han comprado.
 	 **/
  static function Servicio_cliente
	(
		$id_cliente = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$orden = null
	);  
  
  
	
  }
