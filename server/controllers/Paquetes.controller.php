<?php
/**
  *
  *
  *
  **/
	
  class PaquetesController implements IPaquetes{
  
  
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
	public function Nuevo
	(
		$nombre, 
		$empresas, 
		$sucursales, 
		$productos = null, 
		$sericios = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$foto_paquete = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de un paquete
 	 *
 	 * @param margen_utilidad float Margen de utilidad que se ganara al vender este paquete
 	 * @param nombre string Nombre del paquete
 	 * @param id_paquete int ID del paquete a editar
 	 * @param descripcion string Descripcion larga del paquete
 	 * @param servicios json Objeto que contendra los ids de los servicios contenidos en el paquete con sus cantidades respectivas
 	 * @param productos json Objeto que contendra los ids de los productos contenidos en el paquete con sus cantidades respectivas
 	 * @param descuento float Descuento que sera aplicado a este paquete
 	 * @param foto_paquete string Url de la foto del paquete
 	 **/
	public function Editar
	(
		$margen_utilidad, 
		$nombre, 
		$id_paquete, 
		$descripcion = null, 
		$servicios = null, 
		$productos = null, 
		$descuento = null, 
		$foto_paquete = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva un paquete.
 	 *
 	 * @param id_paquete int Id del paquete a desactivar
 	 **/
	public function Eliminar
	(
		$id_paquete
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Activa un paquete previamente desactivado
 	 *
 	 * @param id_paquete int Id del paquete a activar
 	 **/
	public function Activar
	(
		$id_paquete
	)
	{  
  
  
	}
  
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
	public function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$activo = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Muestra los productos y/o servicios englobados en este paquete as?omo las sucursales y las empresas donde lo ofrecen
 	 *
 	 * @param id_paquete int Id del paquete a visualizar sus detalles
 	 * @return detalle_paquete json Informacion del detalle del paquete
 	 **/
	public function Detalle
	(
		$id_paquete
	)
	{  
  
  
	}
  }
