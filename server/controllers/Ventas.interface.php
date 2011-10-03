<?php
/**
  *
  *
  *
  **/

  interaface IVentas {
  
  
	/**
 	 *
 	 *Realiza una nueva venta por arpillas. Este método tiene que llamarse en conjunto con el metodo api/ventas/nueva.
 	 *
 	 **/
	protected function Nueva_venta_arpillas();  
  
  
  
  
	/**
 	 *
 	 *Lista el detalle de una venta, se puede ordenar de acuerdo a sus atributos.
 	 *
 	 **/
	protected function Detalle();  
  
  
  
  
	/**
 	 *
 	 *Lista las ventas, puede filtrarse por empresa, sucursal, por el total, si estan liquidadas o no, por canceladas, y puede ordenarse por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Metodo que cancela una venta
 	 *
 	 **/
	protected function Cancelar();  
  
  
  
  
	/**
 	 *
 	 *Muestra el detalle de una venta por arpilla. Este metodo no muestra los productos de una venta, sino los detalles del embarque de la misma. Para ver los productos de una venta refierase a api/ventas/detalle
 	 *
 	 **/
	protected function Detalle_venta_arpilla();  
  
  
  
  
	/**
 	 *
 	 *Genera una venta fuera de caja, puede usarse para que el administrador venda directamente a clientes especiales. EL usuario y la sucursal seran tomados de la sesion. La fecha se tomara del servidor. La empresa sera tomada del alamacen del que fueron tomados los productos
 	 *
 	 **/
	protected function Nueva();  
  
  
  
  }
