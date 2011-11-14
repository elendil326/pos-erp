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
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @param productos json Objeto que contendra los ids de los productos contenidos en el paquete con sus cantidades respectivas
 	 * @param descuento float Descuento que sera aplicado a este paquete
 	 * @param servicios json Objeto que contendra los ids de los servicios contenidos en el paquete con sus cantidades respectivas
 	 * @param nombre string Nombre del paquete
 	 * @param margen_utilidad float Margen de utilidad que se ganara al vender este paquete
 	 * @param descripcion string Descripcion larga del paquete
 	 **/
  static function Editar
	(
		$id_paquete, 
		$foto_paquete = null, 
		$productos = null, 
		$descuento = null, 
		$servicios = null, 
		$nombre = null, 
		$margen_utilidad = null, 
		$descripcion = null,
                $costo_estandar = null,
                $precio = null
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
 	 * @param id_empresa int Id de la empresa de la cual se listaran los paquetes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus paquetes
 	 * @param id_producto int Se listaran los paquetes que contengan dicho producto
 	 * @param id_servicio int Se listaran los paquetes que contengan dicho servicio
 	 * @param activo bool Si este valor no es obtenido, se listaran paquetes tanto activos como inactivos, si es verdadera, se listaran solo paquetes activos, si es falso, se listaran paquetes inactivos
 	 * @return paquetes json Lista de apquetes
 	 **/
  static function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null,  
		$activo = null
	);  
  
  
	
  
	/**
 	 *
 	 *Agrupa productos y/o servicios en un paquete
 	 *
 	 * @param nombre string Nombre del paquete
 	 * @param empresas json Ids de empresas en las que se ofrecera este paquete
 	 * @param sucursales json Ids de sucursales en las que se ofrecera este paquete
 	 * @param productos json Objeto que contendra los ids de los productos que se incluiran en el paquete con sus cantidades respectivas.
 	 * @param sericios json Objeto que contendra los ids de los servicios que se incluiran en el paquete con sus cantidades respectivas.
 	 * @param descripcion string Descripcion larga del paquete
 	 * @param margen_utilidad float Margen de utilidad que se obtendra al vender este paquete
 	 * @param descuento float Descuento que aplicara a este paquete
 	 * @param foto_paquete string Url de la foto del paquete
 	 * @return id_paquete int Id autogenerado por la insercion
 	 **/
  static function Nuevo
	(
		$nombre, 
		$empresas, 
		$sucursales, 
		$productos = null, 
		$servicios = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$foto_paquete = null,
                $costo_estandar = null,
                $precio = null
	);  
  
  
	
  }
