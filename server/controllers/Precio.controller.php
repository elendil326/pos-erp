<?php
require_once("Precio.interface.php");
/**
  *
  *
  *
  **/
	
  class PrecioController implements IPrecio{
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
	public function Eliminar_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un usuario
 	 *
 	 * @param id_usuario int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Editar_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un usuario
 	 *
 	 * @param id_usuario string 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
	public function Eliminar_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Relaciona un usuario con servicios a un precio o utilidad 
 	 *
 	 * @param id_usuario int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Nuevo_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios servicios con un rol
 	 *
 	 * @param id_rol int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Editar_precio_rolServicio
	(
		$id_rol, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un tipo de cliente
 	 *
 	 * @param id_tipo_cliente json 
 	 * @param servicios_precios_utilidad int Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Editar_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un rol
 	 *
 	 * @param id_rol int Id del rol a quitar el precio preferencial
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
	public function Eliminar_precio_rolProducto
	(
		$id_rol, 
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
	public function Eliminar_precio_tipo_clienteProducto
	(
		$id_tipo_cliente, 
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un usuario
 	 *
 	 * @param id_usuario int 
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
	public function Eliminar_precio_usuarioProducto
	(
		$id_usuario, 
		$productos
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un rol
 	 *
 	 * @param id_rol int 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
	public function Eliminar_precio_rolServicio
	(
		$id_rol, 
		$servicios
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende. 
 	 *
 	 * @param id_rol int Id del rol al que se le asignara este precio o margen de utilidad preferencial
 	 * @param productos_precios_utlidad json Arreglo de objetos que contendran un id producto con un precio o margen de utilidad con el que se vendera este producto
 	 **/
	public function Nuevo_precio_rolProducto
	(
		$id_rol, 
		$productos_precios_utlidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios productos con un rol
 	 *
 	 * @param productos_precios_utlidad json Arreglo de objetos que contendran un id producto con un precio o margen de utilidad con el que se vendera este producto
 	 * @param id_rol int Id del rol al que se le asignara el precio preferencial
 	 **/
	public function Editar_precio_rolProducto
	(
		$productos_precios_utlidad, 
		$id_rol
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Relaciona un rol con productos al precio o utilidad que se le seran vendidos
 	 *
 	 * @param id_rol int Id del rol al que se le asignara el precio preferencial
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Nuevo_precio_rolServicio
	(
		$id_rol, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public function Nuevo_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo relaciona un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 * @param id_tipo_cliente int Id del tipo cliente a relacionar
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
	public function Nuevo_precio_tipo_clienteProducto
	(
		$id_tipo_cliente, 
		$productos_precios_utilidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo edita la relacion de un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 * @param productos_precios_utlidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 * @param id_clasificacion_cliente int Id del tipo cliente al que se le editaran sus precios
 	 **/
	public function Editar_precio_tipo_clienteProducto
	(
		$productos_precios_utlidad, 
		$id_clasificacion_cliente
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al usuario al que se le venda. Este metodo relaciona uno o varios productos con un usuario mediante un precio o margen de utilidad especifico.
 	 *
 	 * @param id_usuario int Id del usuario al que se relacionara
 	 * @param productos_precios_utlidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
	public function Nuevo_precio_usuarioProducto
	(
		$id_usuario, 
		$productos_precios_utlidad
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al cliente al que se le venda. Este metodo relaciona uno o varios productos con un cliente mediante un precio o margen de utilidad especifico.
 	 *
 	 * @param id_cliente int Id del cliente al que se relacionara
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
	public function Editar_precio_usuarioProducto
	(
		$id_cliente, 
		$productos_precios_utilidad
	)
	{  
  
  
	}
  }
