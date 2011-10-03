<?php
/**
  *
  *
  *
  **/

  interaface IConsignaciones {
  
  
	/**
 	 *
 	 *Desactiva la bandera de consignatario a un cliente y elimina su almacen correspondiente. Para poder hacer esto, el almacen debera estar vacio.
 	 *
 	 **/
	protected function DesactivarConsignatario();  
  
  
  
  
	/**
 	 *
 	 *Iniciar una orden de consignación. La fecha sera tomada del servidor.
 	 *
 	 **/
	protected function Nueva();  
  
  
  
  
	/**
 	 *
 	 *Un consignatario ya es un cliente. Al crear un nuevo consignatario, se le crea un nuevo almacen a la sucursal que hace la consignacion. El nombre de ese almacen sera el rfc o la clave del cliente. Se agregara este nuevo id_almacen al cliente y su bandera de consignatario se pondra activa.
 	 *
 	 **/
	protected function NuevoConsignatario();  
  
  
  
  
	/**
 	 *
 	 *Este metodo lista las consignaciones de la instancia. Puede filtrarse por empresa, por sucursal, por cliente, por producto y se puede ordenar por sus atributos.
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Se llama cuando se realiza una revision a una orden de consigacion. Actualiza el estado del almacen del cliente, se facturan a credito o de contado las ventas realizadas y se registra la entrada de dinero. La fecha sera tomada del servidor.
 	 *
 	 **/
	protected function RegistrarInspeccion();  
  
  
  
  
	/**
 	 *
 	 *Dar por terminada una consignacion, bien se termino el inventario en consigacion o se va a regresar al inventario de alguna sucursal.
 	 *
 	 **/
	protected function Terminar();  
  
  
  
  
	/**
 	 *
 	 *Abona un monto de dinero a una inspeccion ya registrada. La fecha sera tomada del sistema. Este metodo sera usado cuando el inspector llegue a la sucursal y deposite el dinero en la caja, de tal forma que se lleve un registro de cuando y cuanto deposito el dinero por el pago de la consignacion, asi como saber si el inspector ya realizo el deposito del dinero que se le consigno.
 	 *
 	 **/
	protected function AbonarInspeccion();  
  
  
  
  
	/**
 	 *
 	 *Registra en que fecha se le hara una inspeccion a un cliente con consignacion 
 	 *
 	 **/
	protected function NuevaInspeccion();  
  
  
  
  
	/**
 	 *
 	 *Usese este metodo cuando se posterga o se adelanta una inspeccion
 	 *
 	 **/
	protected function Cambiar_fechaInspeccion();  
  
  
  
  
	/**
 	 *
 	 *Este metodo sirve para cancelar una inspeccion que aun no ha sido registrada. Sirve cuando se cancela una orden de consignacion y por consiguiente se tienen que cancelar las inspecciones programadas para esa consignacion.
 	 *
 	 **/
	protected function CancelarInspeccion();  
  
  
  
  
	/**
 	 *
 	 *Edita una consignacion, ya sea que agregue o quite productos a la misma. La fecha se toma del sistema.
 	 *
 	 **/
	protected function Editar();  
  
  
  
  }
