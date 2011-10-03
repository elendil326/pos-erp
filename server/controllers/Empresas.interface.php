<?php
/**
  *
  *
  *
  **/

  interaface IEmpresas {
  
  
	/**
 	 *
 	 *Mostrará todas la empresas en el sistema, así como sus sucursalse y sus gerentes[a] correspondientes. Por default no se mostraran las empresas ni sucursales inactivas. 
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Relacionar una sucursal a esta empresa. Cuando se llama a este metodo, se crea un almacen de esta sucursal para esta empresa
 	 *
 	 **/
	protected function Agregar_sucursales();  
  
  
  
  
	/**
 	 *
 	 *Crear una nueva empresa. Por default una nueva empresa no tiene sucursales.
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Para poder eliminar una empresa es necesario que la empresa no tenga sucursales activas, sus saldos sean 0, que los clientes asociados a dicha empresa no tengan adeudo, ...
 	 *
 	 **/
	protected function Eliminar();  
  
  
  
  
	/**
 	 *
 	 *Un administrador puede editar una sucursal, incuso si hay puntos de venta con sesiones activas que pertenecen a esa empresa. 
 	 *
 	 **/
	protected function Editar();  
  
  
  
  }
