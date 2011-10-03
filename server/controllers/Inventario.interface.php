<?php
/**
  *
  *
  *
  **/

  interaface IInventario {
  
  
	/**
 	 *
 	 *Ver la lista de productos y sus existencias, se puede filtrar por empresa, sucursal, almacén, y lote.
Se puede ordenar por los atributos de producto. 
 	 *
 	 **/
	protected function Existencias();  
  
  
  
  
	/**
 	 *
 	 *Procesar producto no es mas que moverlo de lote.
 	 *
 	 **/
	protected function Procesar_producto();  
  
  
  
  
	/**
 	 *
 	 *ver transporte y fletes...
 	 *
 	 **/
	protected function Terminar_cargamento_de_compra();  
  
  
  
  
	/**
 	 *
 	 *Lista todas las compras de una sucursal.
 	 *
 	 **/
	protected function Compras_sucursal();  
  
  
  
  
	/**
 	 *
 	 *Crea un traspaso solicitando cierta cantidad de producto a un almacen para depositarlo en un almacen destino.El usuario sera tomado de la sesion, la fecha sera toamda del servidor
 	 *
 	 **/
	protected function SolicitudTraspaso();  
  
  
  
  
	/**
 	 *
 	 *Este metodo cancela una solicitud de traspaso. Un traspaso solo se puede cancelar si su estado sigue en solicitud. Cuando ya se ha enviado producto o ya se recibio, es imposible cancelar el traspaso
 	 *
 	 **/
	protected function CancelarTraspaso();  
  
  
  
  
	/**
 	 *
 	 *Lista las ventas de una sucursal.
 	 *
 	 **/
	protected function Ventas_sucursal();  
  
  
  
  
	/**
 	 *
 	 *Este metodo edita un traspaso ya solicitado o genera un envio de un almacen a otro sin necesidad de que haya sido solicitado primero, en caso de que haya un excedente en el almacen y tenga que ser enviado a otro. Este metodo peude relacionarse con transportes y fletes. El usuario sera tomado de la sesion actual. La fecha sera tomada del servidor. 
 	 *
 	 **/
	protected function EnvioTraspaso();  
  
  
  
  
	/**
 	 *
 	 *Registra que se recibio el traspaso de productos. El usuario sera tomado de la sesion actual y la fecha sera tomada del servidor. Este metodo es equivalente a una edicion, ya que da por realizado el traspaso e incrementa el producto en almacen
 	 *
 	 **/
	protected function ReciboTraspaso();  
  
  
  
  }
