<?php
/**
  *
  *
  *
  **/
	
  interface IPaquetes {
  
  
	/**
 	 *
 	 *Activa un paquete previamente desactivado
 	 *
 	 * @param id_paquete int Id del paquete a activar
 	 **/
  static function Activar
	(
		$id_paquete
	);  
  
  
	
  
	/**
 	 *
 	 *Muestra los productos y/o servicios englobados en este paquete as? como las sucursales y las empresas donde lo ofrecen
 	 *
 	 * @param id_paquete int Id del paquete a visualizar sus detalles
 	 * @return detalle_paquete json Informacion del detalle del paquete
 	 **/
  static function Detalle
	(
		$id_paquete
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la informacion de un paquete
 	 *
 	 * @param id_paquete int ID del paquete a editar
 	 * @param costo_estandar float Costo estandar del paquete
 	 * @param descripcion string Descripcion larga del paquete
 	 * @param descuento float Descuento que sera aplicado a este paquete
 	 * @param empresas json Objeto que contendra la informacion de las empresas a las que pertenecera este paquete
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @param margen_utilidad float Margen de utilidad que se ganara al vender este paquete
 	 * @param nombre string Nombre del paquete
 	 * @param precio float Precio del paquete
 	 * @param productos json Objeto que contendra los ids de los productos contenidos en el paquete con sus cantidades respectivas
 	 * @param servicios json Objeto que contendra los ids de los servicios contenidos en el paquete con sus cantidades respectivas
 	 * @param sucursales json Objeto que contendra la informacion de las sucursales a las cuales pertenecera este paquete
 	 **/
  static function Editar
	(
		$id_paquete, 
		$costo_estandar = null, 
		$descripcion = null, 
		$descuento = null, 
		$empresas = null, 
		$foto_paquete = null, 
		$margen_utilidad = null, 
		$nombre = null, 
		$precio = null, 
		$productos = null, 
		$servicios = null, 
		$sucursales = null
	);  
  
  
	
  
	/**
 	 *
 	 *Desactiva un paquete.
 	 *
 	 * @param id_paquete int Id del paquete a desactivar
 	 **/
  static function Eliminar
	(
		$id_paquete
	);  
  
  
	
  
	/**
 	 *
 	 *Lista los paquetes, se puede filtrar por empresa, por sucursal, por producto, por servicio y se pueden ordenar por sus atributos
 	 *
 	 * @param activo bool Si este valor no es obtenido, se listaran paquetes tanto activos como inactivos, si es verdadera, se listaran solo paquetes activos, si es falso, se listaran paquetes inactivos
 	 * @param id_empresa int Id de la empresa de la cual se listaran los paquetes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus paquetes
 	 * @return paquetes json Lista de apquetes
 	 **/
  static function Lista
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agrupa productos y/o servicios en un paquete
 	 *
 	 * @param empresas json Objeto que contendra la informacion de las empresas a las que pertenecera este paquete
 	 * @param nombre string Nombre del paquete
 	 * @param scursales json Objeto que contendra la informacion de las sucursales a las que pertenecera este paquete
 	 * @param costo_estandar float COsto estandar del paquete
 	 * @param descripcion string Descripcion larga del paquete
 	 * @param descuento float Descuento que aplicara a este paquete
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @param margen_utilidad float Margen de utilidad que se obtendra al vender este paquete
 	 * @param precio float Precio del paquete
 	 * @param productos json productos que contendra este paquete
 	 * @param servicios json servicios que contendra este paquete
 	 * @return id_paquete int Id autogenerado por la insercion
 	 **/
  static function Nuevo
	(
		$empresas, 
		$nombre, 
		$scursales, 
		$costo_estandar = null, 
		$descripcion = null, 
		$descuento = null, 
		$foto_paquete = null, 
		$margen_utilidad = null, 
		$precio = null, 
		$productos = null, 
		$servicios = null
	);  
  
  
	
  }
