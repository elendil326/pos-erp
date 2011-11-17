<?php
/**
  *
  *
  *
  **/
	
  interface IInventario {
  
  
	/**
 	 *
 	 *Lista todas las compras de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus compras
 	 * @return compras json Arreglo de objetos que tendr� las compras de la sucursal
 	 **/
  static function Compras_sucursal
	(
		$id_sucursal
	);  
  
  
	
  
	/**
 	 *
 	 *Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac?n, y lote.
Se puede ordenar por los atributos de producto. 
 	 *
 	 * @param existencia_mayor_que float Se regresaran los productos cuya existencia sea mayor a la especificada por este valor
 	 * @param existencia_igual_que float Se regresaran los productos cuya existencia sea igual a la especificada por este valor
 	 * @param existencia_menor_que float Se regresaran los productos cuya existencia sea menor a la especificada por este valor
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos.
 	 * @param id_sucursal int Id de la sucursal de la cual se vern los productos.
 	 * @param id_almacen	 int Id del almacen del cual se vern los productos.
 	 * @param activo	 bool Si es true, mostrar solo los productos que estn activos, si es false mostrar solo los productos que no lo sean.
 	 * @param id_lote int Id del lote del cual se veran los productos en existencia
 	 * @return existecias json Lista de existencias
 	 **/
  static function Existencias
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_almacen	 = null, 
		$id_producto	 = null
	);  
  
  
	
  
	/**
 	 *
 	 *Procesar producto no es mas que moverlo de lote.
 	 *
 	 * @param id_lote_nuevo int Id del lote al que se mover el producto
 	 * @param id_producto int Id del producto a mover
 	 * @param id_lote_viejo int Id del lote donde se encontraba el producto
 	 * @param cantidad float Si solo se movera una cierta cantidad de producto al nuevo lote. Si este valor no es obtenido, se da por hecho que se movera toda la cantidad de ese producto al nuevo lote
 	 **/
  static function Procesar_producto
	(
		$id_lote_nuevo, 
		$id_producto, 
		$id_lote_viejo, 
		$cantidad = null
	);  
  
  
	
  
	/**
 	 *
 	 *ver transporte y fletes...
 	 *
 	 **/
  static function Terminar_cargamento_de_compra
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las ventas de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual listaran sus ventas
 	 * @return ventas json Objeto que conendra la informacion de las ventas de esa sucursal
 	 **/
  static function Ventas_sucursal
	(
		$id_sucursal
	);  
  
  
	
  }
