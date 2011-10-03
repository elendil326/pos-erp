<?php
/**
  *
  *
  *
  **/

  interaface IProveedores {
  
  
	/**
 	 *
 	 *Desactiva una clasificacion de proveedor
 	 *
 	 **/
	protected function EliminarClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Crea una nueva clasificacion de proveedor
 	 *
 	 **/
	protected function NuevaClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de una clasificacion de proveedor
 	 *
 	 **/
	protected function EditarClasificacion();  
  
  
  
  
	/**
 	 *
 	 *Obtener una lista de proveedores. Puede filtrarse por activo o inactivos, y puede ordenarse por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Crea un nuevo proveedor
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un proveedor. 
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Este metodo desactiva un proveedor. Para que este metodo funcione, no debe de haber ordenes de compra hacia ese proveedor <b>??</b>
 	 *
 	 **/
	protected function Eliminar();  
  
  
  
  }
