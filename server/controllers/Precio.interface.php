<?php
/**
  *
  *
  *
  **/

  interaface IPrecio {
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un tipo de cliente
 	 *
 	 **/
	protected function Eliminar_precio_tipo_clienteServicio();  
  
  
  
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un usuario
 	 *
 	 **/
	protected function Editar_precio_usuarioServicio();  
  
  
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un usuario
 	 *
 	 **/
	protected function Eliminar_precio_usuarioServicio();  
  
  
  
  
	/**
 	 *
 	 *Relaciona un usuario con servicios a un precio o utilidad 
 	 *
 	 **/
	protected function Nuevo_precio_usuarioServicio();  
  
  
  
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios servicios con un rol
 	 *
 	 **/
	protected function Editar_precio_rolServicio();  
  
  
  
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un tipo de cliente
 	 *
 	 **/
	protected function Editar_precio_tipo_clienteServicio();  
  
  
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un rol
 	 *
 	 **/
	protected function Eliminar_precio_rolProducto();  
  
  
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un tipo de cliente
 	 *
 	 **/
	protected function Eliminar_precio_tipo_clienteProducto();  
  
  
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un producto con un usuario
 	 *
 	 **/
	protected function Eliminar_precio_usuarioProducto();  
  
  
  
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un rol
 	 *
 	 **/
	protected function Eliminar_precio_rolServicio();  
  
  
  
  
	/**
 	 *
 	 *Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende. 
 	 *
 	 **/
	protected function Nuevo_precio_rolProducto();  
  
  
  
  
	/**
 	 *
 	 *Edita la relacion de precio de uno o varios productos con un rol
 	 *
 	 **/
	protected function Editar_precio_rolProducto();  
  
  
  
  
	/**
 	 *
 	 *Relaciona un rol con productos al precio o utilidad que se le seran vendidos
 	 *
 	 **/
	protected function Nuevo_precio_rolServicio();  
  
  
  
  
	/**
 	 *
 	 *Relaciona un tipo de cliente con servicios a un precio o utilidad determinados
 	 *
 	 **/
	protected function Nuevo_precio_tipo_clienteServicio();  
  
  
  
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo relaciona un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 **/
	protected function Nuevo_precio_tipo_clienteProducto();  
  
  
  
  
	/**
 	 *
 	 *Los precios de un producto pueden variar segun el tipo de cliente al que se le vende. Este metodo edita la relacion de un precio a uno o varios productos con un tipo de cliente.
 	 *
 	 **/
	protected function Editar_precio_tipo_clienteProducto();  
  
  
  
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al usuario al que se le venda. Este metodo relaciona uno o varios productos con un usuario mediante un precio o margen de utilidad especifico.
 	 *
 	 **/
	protected function Nuevo_precio_usuarioProducto();  
  
  
  
  
	/**
 	 *
 	 *El precio de un producto puede varior de acuerdo al cliente al que se le venda. Este metodo relaciona uno o varios productos con un cliente mediante un precio o margen de utilidad especifico.
 	 *
 	 **/
	protected function Editar_precio_usuarioProducto();  
  
  
  
  }
