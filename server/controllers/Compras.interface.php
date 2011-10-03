<?php
/**
  *
  *
  *
  **/

  interaface ICompras {
  
  
	/**
 	 *
 	 *Lista las compras. Se puede filtrar por empresa, sucursal, caja, usuario que registra la compra, usuario al que se le compra, tipo de compra, si estan pagadas o no, por tipo de pago, canceladas o no, por el total, por fecha, por el tipo de pago y se puede ordenar por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Registra una nueva compra por arpillas. Este metodo tiene que usarse en conjunto con el metodo api/compras/nueva
<br/><br/><b>Update :</b> Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 **/
	protected function Nueva_compra_arpilla();  
  
  
  
  
	/**
 	 *
 	 *Cancela una compra
 	 *
 	 **/
	protected function Cancelar();  
  
  
  
  
	/**
 	 *
 	 *Muestra el detalle de una compra
 	 *
 	 **/
	protected function Detalle();  
  
  
  
  
	/**
 	 *
 	 *Muestra el detalle de una compra por arpillas. Este detalle no es el detalle por producto, este muestra los detalles por embarque de la compra. Para el detalle por producto refierase a api/compras/detalle

<br/><br/><b>Update :</b> Todo este metodo esta mal, habria que definir nuevamente como se van a manejar las compras a los proveedores ya que como esta definido aqui solo funcionaria para el POS de las papas.
 	 *
 	 **/
	protected function Detalle_compra_arpilla();  
  
  
  
  
	/**
 	 *
 	 *Registra una nueva compra fuera de caja, puede usarse para que el administrador haga directamente una compra. El usuario y al sucursal seran tomados de la sesion. La fecha sera tomada del servidor. La empresa sera tomada del almacen del cual fueron tomados los productos.
 	 *
 	 **/
	protected function Nueva();  
  
  
  
  }
