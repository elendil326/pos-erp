<?php
/**
  *
  *
  *
  **/
	
  interface IPrecio {
  
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios paquetes con un rol
 	 *
 	 * @param id_rol int Id del rol al que se le asignara el precio preferencial
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o margen de utilidad con el que se vendera este producto
 	 **/
  static function Editar_precio_rolPaquete
	(
		$id_rol, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios paquetes para un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int Id del tipo cliente al que se le editaran sus precios
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o un margen de utilidad con el que se ofrecera dicho paquete
 	 **/
  static function Editar_precio_tipo_clientePaquete
	(
		$id_tipo_cliente, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios paquetes para un usuario
 	 *
 	 * @param id_usuario int Id del usuario al que se relacionara
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o un margen de utilidad con el que se ofrecera dicho paquete
 	 **/
  static function Editar_precio_usuarioPaquete
	(
		$id_usuario, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un paquete con un rol
 	 *
 	 * @param id_rol int Id del rol a quitar el precio preferencial
 	 * @param paquetes json Arreglo de ids de paquetes a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_rolPaquete
	(
		$id_rol, 
		$paquetes
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un paquete con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int Id del tipo de cliente al que se le asignara el precio preferencial
 	 * @param paquetes json Arreglo de ids de paquetes a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_tipo_clientePaquete
	(
		$id_tipo_cliente, 
		$paquetes
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un paquete con un usuario
 	 *
 	 * @param id_usuario int Id del usuario a relacionar
 	 * @param paquetes json Arreglo de ids de paquetes a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_usuarioPaquete
	(
		$id_usuario, 
		$paquetes
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un rol con paquetess al precio o utilidad que se le seran vendidos
 	 *
 	 * @param id_rol int Id del rol al que se le asignara este precio o margen de utilidad preferencial
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o margen de utilidad con el que se vendera este paquete
 	 **/
  static function Nuevo_precio_rolPaquete
	(
		$id_rol, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un tipo de cliente con paquetes a un precio o utilidad determinados
 	 *
 	 * @param id_tipo_cliente int Id del tipo cliente a relacionar
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o un margen de utilidad con el que se ofrecera dicho paquete
 	 **/
  static function Nuevo_precio_tipo_clientePaquete
	(
		$id_tipo_cliente, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un usuario con paquetes a un precio o utilidad 
 	 *
 	 * @param id_usuario int Id del usuario al que se relacionara
 	 * @param paquetes_precios_utilidad json Arreglo de objetos que contendran un id paquete con un precio o un margen de utilidad con el que se ofrecera dicho paquete
 	 **/
  static function Nuevo_precio_usuarioPaquete
	(
		$id_usuario, 
		$paquetes_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios productos con un rol
 	 *
 	 * @param id_rol int Id del rol al que se le asignara el precio preferencial
 	 * @param productos_precios_utlidad json Arreglo de objetos que contendran un id producto con un precio o margen de utilidad con el que se vendera este producto
 	 **/
  static function Editar_precio_rolProducto
	(
		$id_rol, 
		$productos_precios_utlidad
	);  
  
  
	
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo edita la relacion de un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 * @param id_clasificacion_cliente int Id del tipo cliente al que se le editaran sus precios
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
  static function Editar_precio_tipo_clienteProducto
	(
		$id_clasificacion_cliente, 
		$productos_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al cliente al que se le venda. Este metodo relaciona uno o varios productos con un cliente mediante un precio o margen de utilidad especifico.
 	 *
 	 * @param id_usuario int Id del cliente al que se relacionara
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
  static function Editar_precio_usuarioProducto
	(
		$id_usuario, 
		$productos_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un rol
 	 *
 	 * @param id_rol int Id del rol a quitar el precio preferencial
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_rolProducto
	(
		$id_rol, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_tipo_clienteProducto
	(
		$id_tipo_cliente, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un usuario
 	 *
 	 * @param id_usuario int 
 	 * @param productos json Arreglo de ids de productos a los que se les quitara el precio preferencial
 	 **/
  static function Eliminar_precio_usuarioProducto
	(
		$id_usuario, 
		$productos
	);  
  
  
	
  
	/**
 	 *
 	 *Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende. 
 	 *
 	 * @param id_rol int Id del rol al que se le asignara este precio o margen de utilidad preferencial
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o margen de utilidad con el que se vendera este producto
 	 **/
  static function Nuevo_precio_rolProducto
	(
		$id_rol, 
		$productos_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo relaciona un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 * @param id_tipo_cliente int Id del tipo cliente a relacionar
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
  static function Nuevo_precio_tipo_clienteProducto
	(
		$id_tipo_cliente, 
		$productos_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al usuario al que se le venda. Este metodo relaciona uno o varios productos con un usuario mediante un precio o margen de utilidad especifico.
 	 *
 	 * @param id_usuario int Id del usuario al que se relacionara
 	 * @param productos_precios_utilidad json Arreglo de objetos que contendran un id producto con un precio o un margen de utilidad con el que se ofrecera dicho producto
 	 **/
  static function Nuevo_precio_usuarioProducto
	(
		$id_usuario, 
		$productos_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios servicios con un rol
 	 *
 	 * @param id_rol int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Editar_precio_rolServicio
	(
		$id_rol, 
		$servicios_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un tipo de cliente
 	 *
 	 * @param id_tipo_cliente json 
 	 * @param servicios_precios_utilidad int Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Editar_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un usuario
 	 *
 	 * @param id_usuario int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Editar_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un rol
 	 *
 	 * @param id_rol int 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
  static function Eliminar_precio_rolServicio
	(
		$id_rol, 
		$servicios
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
  static function Eliminar_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios
	);  
  
  
	
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un usuario
 	 *
 	 * @param id_usuario string 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
  static function Eliminar_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un rol con productos al precio o utilidad que se le seran vendidos
 	 *
 	 * @param id_rol int Id del rol al que se le asignara el precio preferencial
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Nuevo_precio_rolServicio
	(
		$id_rol, 
		$servicios_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Nuevo_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios_precios_utilidad
	);  
  
  
	
  
	/**
 	 *
 	 *Relaciona un usuario con servicios a un precio o utilidad 
 	 *
 	 * @param id_usuario int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
  static function Nuevo_precio_usuarioServicio
	(
		$id_usuario, 
		$servicios_precios_utilidad
	);  
  
  
	
  }
