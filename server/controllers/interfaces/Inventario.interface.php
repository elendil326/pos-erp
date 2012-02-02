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
 	 * @return compras json Arreglo de objetos que tendrá las compras de la sucursal
 	 **/
  static function SucursalCompras
	(
		$id_sucursal
	);  
  
  
	
  
	/**
 	 *
 	 *Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac?n, y producto.
 	 *
 	 * @param id_almacen	 int Id del almacen del cual se vern los productos. Si este valor es obtenido no se tomaran en cuenta los parametros id_empresa ni id_sucursal
 	 * @param id_empresa int Id de la empresa de la cual se vern los productos. Si este valor es obtenido no se tomara en cuenta el valor de id_sucursal
 	 * @param id_producto int Mostrara las existencias de ese producto. Se puede convinar con los demas parametros. Si solo se recibe este parametro, se regresara un arreglo con las existencias de este prodcto en cada sucursal de acuerdo a su unidad.
 	 * @param id_sucursal int Id de la sucursal de la cual se vern los productos.
 	 * @return existecias json Lista de existencias
 	 **/
  static function Existencias
	(
		$id_almacen	 = null, 
		$id_empresa = null, 
		$id_producto = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Procesar producto no es mas que moverlo de lote.
 	 *
 	 * @param cantidad_nueva float La cantidad de producto nuevo que se procesara
 	 * @param cantidad_vieja float La cantidad de producto viejo que se procesara
 	 * @param id_almacen_nuevo int Id del almacen al que se mover el producto
 	 * @param id_almacen_viejo int Id del lote donde se encontraba el producto
 	 * @param id_producto_nuevo int Id del producto ya transformado
 	 * @param id_producto_viejo int Id del producto a mover
 	 * @param id_unidad_nueva int Id de la unidad nueva a la que se transformara
 	 * @param id_unidad_vieja int Id de la unidad en la que se encunetra el producto nuevo
 	 **/
  static function ProductoProcesar
	(
		$cantidad_nueva, 
		$cantidad_vieja, 
		$id_almacen_nuevo, 
		$id_almacen_viejo, 
		$id_producto_nuevo, 
		$id_producto_viejo, 
		$id_unidad_nueva, 
		$id_unidad_vieja
	);  
  
  
	
  
	/**
 	 *
 	 *ver transporte y fletes...
 	 *
 	 **/
  static function CompraDeCargamentoTerminar
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las ventas de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual listaran sus ventas
 	 * @return ventas json Objeto que conendra la informacion de las ventas de esa sucursal
 	 **/
  static function SucursalVentas
	(
		$id_sucursal
	);  
  
  
	
  }
