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
  function Compras_sucursal
	(
		$id_sucursal
	);  
  
  
	
  
	/**
 	 *
 	 *Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almac? y lote.
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
  function Existencias
	(
		$existencia_mayor_que = null, 
		$existencia_igual_que = null, 
		$existencia_menor_que = null, 
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_almacen	 = null, 
		$activo	 = null, 
		$id_lote = null
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
  function Procesar_producto
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
  function Terminar_cargamento_de_compra
	(
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo cancela una solicitud de traspaso. Un traspaso solo se puede cancelar si su estado sigue en solicitud. Cuando ya se ha enviado producto o ya se recibio, es imposible cancelar el traspaso
 	 *
 	 * @param id_traspaso int Id del traspaso a cancelar
 	 **/
  function CancelarTraspaso
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Este metodo edita un traspaso ya solicitado o genera un envio de un almacen a otro sin necesidad de que haya sido solicitado primero, en caso de que haya un excedente en el almacen y tenga que ser enviado a otro. Este metodo peude relacionarse con transportes y fletes. El usuario sera tomado de la sesion actual. La fecha sera tomada del servidor. 
 	 *
 	 * @param productos json Objeto que contendra los ids de los productos que son enviados y sus cantidades
 	 * @param id_traspaso int Id de la solicitud de traspaso a la que se responde con este envio
 	 * @param id_almacen_destino int Id del almacen al que se dirije el envio, en caso de que no haya sido solicitado primero
 	 * @param id_almacen_origen int Id del almacen del que salen los productos
 	 * @return id_traspaso int Si el envio se genero sin haber primero una solicitud, este regresa un id_traspaso, si solo edito un traspaso solicitado agregandole los datos de enviono se regresa este parametro
 	 **/
  function EnvioTraspaso
	(
		$productos, 
		$id_traspaso = null, 
		$id_almacen_destino = null, 
		$id_almacen_origen = null
	);  
  
  
	
  
	/**
 	 *
 	 *Registra que se recibio el traspaso de productos. El usuario sera tomado de la sesion actual y la fecha sera tomada del servidor. Este metodo es equivalente a una edicion, ya que da por realizado el traspaso e incrementa el producto en almacen
 	 *
 	 * @param id_traspaso int Id del traspaso que se recibe
 	 **/
  function ReciboTraspaso
	(
		$id_traspaso
	);  
  
  
	
  
	/**
 	 *
 	 *Crea un traspaso solicitando cierta cantidad de producto a un almacen para depositarlo en un almacen destino.El usuario sera tomado de la sesion, la fecha sera toamda del servidor
 	 *
 	 * @param id_almacen_solicita int Id del almacen que solicita el producto
 	 * @param id_almacen_envia int Id del almacen al que se le solicita el producto
 	 * @param productos json Objeto que contendra los ids de los productos con sus cantidades que se solicitan
 	 * @return id_traspaso int Id del traspaso generado
 	 **/
  function SolicitudTraspaso
	(
		$id_almacen_solicita, 
		$id_almacen_envia, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Lista las ventas de una sucursal.
 	 *
 	 * @param id_sucursal int Id de la sucursal de la cual listaran sus ventas
 	 * @return ventas json Objeto que conendra la informacion de las ventas de esa sucursal
 	 **/
  function Ventas_sucursal
	(
		$id_sucursal
	);  
  
  
	
  }
