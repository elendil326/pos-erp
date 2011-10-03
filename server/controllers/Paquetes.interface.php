<?php
/**
  *
  *
  *
  **/

  interaface IPaquetes {
  
  
	/**
 	 *
 	 *Agrupa productos y/o servicios en un paquete
 	 *
 	 **/
	protected function Nuevo();  
  
  
  
  
	/**
 	 *
 	 *Edita la informacion de un paquete
 	 *
 	 **/
	protected function Editar();  
  
  
  
  
	/**
 	 *
 	 *Desactiva un paquete.
 	 *
 	 **/
	protected function Eliminar();  
  
  
  
  
	/**
 	 *
 	 *Activa un paquete previamente desactivado
 	 *
 	 **/
	protected function Activar();  
  
  
  
  
	/**
 	 *
 	 *Lista los paquetes, se puede filtrar por empresa, por sucursal, por producto, por servicio y se pueden ordenar por sus atributos
 	 *
 	 **/
	protected function Lista();  
  
  
  
  
	/**
 	 *
 	 *Muestra los productos y/o servicios englobados en este paquete así como las sucursales y las empresas donde lo ofrecen
 	 *
 	 **/
	protected function Detalle();  
  
  
  
  }
