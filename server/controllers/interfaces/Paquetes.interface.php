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
 	 *Muestra los productos y/o servicios englobados en este paquete as?omo las sucursales y las empresas donde lo ofrecen
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
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @param margen_utilidad float Margen de utilidad que se ganara al vender este paquete
 	 * @param nombre string Nombre del paquete
 	 * @param precio float Precio del paquete
 	 * @param productos json Objeto que contendra los ids de los productos contenidos en el paquete con sus cantidades respectivas
 	 * @param servicios json Objeto que contendra los ids de los servicios contenidos en el paquete con sus cantidades respectivas
 	 **/
  static function Editar
	(
		$id_paquete, 
		$costo_estandar = null, 
		$descripcion = null, 
		$descuento = null, 
		$foto_paquete = null, 
		$margen_utilidad = null, 
		$nombre = null, 
		$precio = null, 
		$productos = null, 
		$servicios = null
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
 	 * @param nombre string Nombre del paquete
 	 * @param costo_estandar float COsto estandar del paquete
 	 * @param descripcion string Descripcion larga del paquete
 	 * @param descuento float Descuento que aplicara a este paquete
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @param margen_utilidad float Margen de utilidad que se obtendra al vender este paquete
 	 * @param precio float Precio del paquete
 	 * @return id_paquete int Id autogenerado por la insercion
 	 **/
  static function Nuevo
	(
		$nombre, 
		$costo_estandar = null, 
		$descripcion = null, 
		$descuento = null, 
		$foto_paquete = null, 
		$margen_utilidad = null, 
		$precio = null
	);  
  
  
	
  }
