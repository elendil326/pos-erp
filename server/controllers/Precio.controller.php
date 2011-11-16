<?php
require_once("interfaces/Precio.interface.php");
/**
  *
  *
  *
  **/
	
  class PrecioController implements IPrecio{
  
      
        //Metodo para pruebas que simula la obtencion del id de la sucursal actual
        private static function getSucursal()
        {
            return 1;
        }
        
        //metodo para pruebas que simula la obtencion del id de la caja actual
        private static function getCaja()
        {
            return 1;
        }
        
        
        /*
         *Se valida que un string tenga longitud en un rango de un maximo inclusivo y un minimo exclusvio.
         *Regresa true cuando es valido, y un string cuando no lo es.
         */
          private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$string.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
        }


        /*
         * Se valida que un numero este en un rango de un maximo y un minimo inclusivos
         * Regresa true cuando es valido, y un string cuando no lo es
         */
	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}
      
        
        
        
        
        
      
      
      
      
  
	/**
 	 *
 	 *Elimina la relacion del precio de un servicio con un tipo de cliente
 	 *
 	 * @param id_tipo_cliente int 
 	 * @param servicios json Arreglo de ids de servicios que perderan el precio preferencial
 	 **/
	public static function Eliminar_precio_tipo_clienteServicio
	(
		$id_tipo_cliente, 
		$servicios
	)
	{  
            Logger::log("Eliminando el precio de los servicios para el tipo de cliente ".$id_tipo_cliente);
            
            //Se inicializa el registro que se borrara. Se recorrera la lista de servicios obtenida
            //y se eliminaran los registros correspondientes
            DAO::transBegin();
            try
            {
                foreach($servicios as $servicio)
                {
                    $precio_servicio_tipo_cliente = PrecioServicioTipoClienteDAO::getByPK($servicio, $id_tipo_cliente);
                    if(is_null($precio_servicio_tipo_cliente))
                            throw new Exception("El tipo de cliente ".$id_tipo_cliente." no tiene precio preferencial con el servicio ".$servicio);
                    PrecioServicioTipoClienteDAO::delete($precio_servicio_tipo_cliente);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido eliminar los precios: ".$e);
                throw new Exception("No se han podido eliminar todos los precios");
            }
            DAO::transEnd();
            Logger::log("Precios eliminados correctamente");
	}
  
	/**
 	 *
 	 *Edita la relacion de precio con uno o varios servicios para un usuario
 	 *
 	 * @param id_usuario int 
 	 * @param servicios_precios_utilidad json Arreglo de objetos que contendran un id servicio con un precio o margen de utilidad con el que se vendera este servicio
 	 **/
	public static function Editar_precio_usuarioServicio
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
	public static function Eliminar_precio_usuarioServicio
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
	public static function Nuevo_precio_usuarioServicio
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
	public static function Editar_precio_rolServicio
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
	public static function Editar_precio_tipo_clienteServicio
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
	public static function Eliminar_precio_rolProducto
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
	public static function Eliminar_precio_tipo_clienteProducto
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
	public static function Eliminar_precio_usuarioProducto
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
	public static function Eliminar_precio_rolServicio
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
	public static function Nuevo_precio_rolProducto
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
	public static function Editar_precio_rolProducto
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
	public static function Nuevo_precio_rolServicio
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
	public static function Nuevo_precio_tipo_clienteServicio
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
	public static function Nuevo_precio_tipo_clienteProducto
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
	public static function Editar_precio_tipo_clienteProducto
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
	public static function Nuevo_precio_usuarioProducto
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
	public static function Editar_precio_usuarioProducto
	(
		$id_cliente, 
		$productos_precios_utilidad
	)
	{  
  
  
	}
  }
