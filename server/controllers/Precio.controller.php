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
      
        /*
         * Valida el boleano es_margen_utilidad
         */
        private static function validarEsMargenUtilidad($es_margen_utilidad)
        {
            if(!is_null($es_margen_utilidad))
            {
                $e = self::validarNumero($es_margen_utilidad, 1, "es margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el precio utilidad este en rango
         */
        private static function validarPrecioUtilidad($precio_utilidad)
        {
            $e = self::validarNumero($precio_utilidad, 1.8e200, "precio utilidad");
            if(is_string($e))
                return $e;
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el servicio exista y que este activo
         */
        private static function validarServicio($id_servicio)
        {
            $servicio = ServicioDAO::getByPK($id_servicio);
            
            if(is_null($servicio))
                return "El servicio ".$id_servicio." no existe";

            if(!$servicio->getActivo())
                return "El servicio ".$id_servicio." no esta activo";
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el usuario exista y que este activo
         */
        private static function validarUsuario($id_usuario)
        {
            $usuario = UsuarioDAO::getByPK($id_usuario);
            
            if(is_null($usuario))
                return "El usuario ".$id_usuario." no existe";

            if(!$usuario->getActivo())
                return "El usuario ".$id_usuario." no esta activo";
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el producto exista y que este activo
         */
        private static function validarProducto($id_producto)
        {
            $producto = ProductoDAO::getByPK($id_producto);
            if(is_null($producto))
                return "El producto ".$id_producto." no existe";
            
            if(!$producto->getActivo())
                return "EL producto ".$id_producto." no esta activo";
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el tipo de cliente exista
         */
        private static function validarClasificacionCliente($id_clasificacion_cliente)
        {
            if(is_null(ClasificacionClienteDAO::getByPK($id_clasificacion_cliente)))
                    return "La clasificacion de cliente ".$id_clasificacion_cliente." no existe";
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el paquete exista y este activo
         */
        private static function validarPaquete($id_paquete)
        {
            $paquete = PaqueteDAO::getByPK($id_paquete);
            if(is_null($paquete))
                return "El paquete ".$id_paquete." no existe";
            
            if(!$paquete->getActivo())
                return "El paquete ".$id_paquete." no esta activo";
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida que el rol exista
         */
        private static function validarRol($id_rol)
        {
            if(is_null(RolDAO::getByPK($id_rol)))
                return "El rol ".$id_rol." no existe";
            
            //no se encontro error
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
            Logger::log("Editando precios de los servicios para el usuario ".$id_usuario);
            
            //valida al usuario obtendio
            $validar = self::validarUsuario($id_usuario);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a editar. Si alguno de los registros no existe, se guardara
            $precio_servicio_usuario = new PrecioServicioUsuario( array( "id_usuario" => $id_usuario ) );
            DAO::transBegin();
            try
            {
                foreach($servicios_precios_utilidad as $servicio_precio_utilidad)
                {
                    $validar = self::validarServicio($servicio_precio_utilidad["id_servicio"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_servicio_usuario->setEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    $precio_servicio_usuario->setIdServicio($servicio_precio_utilidad["id_servicio"]);
                    $precio_servicio_usuario->setPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    PrecioServicioUsuarioDAO::save($precio_servicio_usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido editar todos los precios para el usuario ".$id_usuario." : ".$e);
                throw new Exception("No se han podido editar todos los precios para el usuario");
            }
            DAO::transEnd();
            Logger::log("Precios editados exitosamente");
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
            Logger::log("ELiminando los precios de servicio para el usuario ".$id_usuario);
            
            //Se inicializa el registro a eliminar y se elimina
            DAO::transBegin();
            try
            {
                foreach($servicios as $servicio)
                {
                    $precio_servicio_usuario = PrecioServicioUsuarioDAO::getByPK($servicio, $id_usuario);
                    if(is_null($precio_servicio_usuario))
                        throw new Exception("El usuario ".$id_usuario." no tiene precio especial para el servicio ".$servicio);
                    PrecioServicioUsuarioDAO::delete($precio_servicio_usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron eliminar los precios para servicio del usuario ".$id_usuario." : ".$e);
                throw new Exception("No se pudieron eliminar los precios para servicio del usuario");
            }
            DAO::transEnd();
            Logger::log("Precios de servicios eliminados exitosamente");
            
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
            Logger::log("Registrando precios de los servicios para el usuario ".$id_usuario);
            
            //valida al usuario obtendio
            $validar = self::validarUsuario($id_usuario);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro y se guarda
            $precio_servicio_usuario = new PrecioServicioUsuario( array( "id_usuario" => $id_usuario ) );
            DAO::transBegin();
            try
            {
                foreach($servicios_precios_utilidad as $servicio_precio_utilidad)
                {
                    $validar = self::validarServicio($servicio_precio_utilidad["id_servicio"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_servicio_usuario->setEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    $precio_servicio_usuario->setIdServicio($servicio_precio_utilidad["id_servicio"]);
                    $precio_servicio_usuario->setPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    PrecioServicioUsuarioDAO::save($precio_servicio_usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido guardar todos los precios para el usuario ".$id_usuario." : ".$e);
                throw new Exception("No se han podido guardar todos los precios para el usuario");
            }
            DAO::transEnd();
            Logger::log("Precios guardados exitosamente");
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
            Logger::log("Editando precios de los servicios para el rol ".$id_rol);
            
            //valida al rol obtendio
            $validar = self::validarRol($id_rol);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a editar. Si alguno de los registros no existe, se guardara
            $precio_servicio_rol = new PrecioServicioRol( array( "id_rol" => $id_rol ) );
            DAO::transBegin();
            try
            {
                foreach($servicios_precios_utilidad as $servicio_precio_utilidad)
                {
                    $validar = self::validarServicio($servicio_precio_utilidad["id_servicio"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_servicio_rol->setEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    $precio_servicio_rol->setIdServicio($servicio_precio_utilidad["id_servicio"]);
                    $precio_servicio_rol->setPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    PrecioServicioRolDAO::save($precio_servicio_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido editar todos los precios para el rol ".$id_rol." : ".$e);
                throw new Exception("No se han podido editar todos los precios para el rol");
            }
            DAO::transEnd();
            Logger::log("Precios editados exitosamente");
            
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
            Logger::log("Editando precios de los servicios para el tipo_cliente ".$id_tipo_cliente);
            
            //valida al tipo_cliente obtendio
            $validar = self::validarClasificacionCliente($id_tipo_cliente);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a editar. Si alguno de los registros no existe, se guardara
            $precio_servicio_tipo_cliente = new PrecioServicioTipoCliente( array( "id_tipo_cliente" => $id_tipo_cliente ) );
            DAO::transBegin();
            try
            {
                foreach($servicios_precios_utilidad as $servicio_precio_utilidad)
                {
                    $validar = self::validarServicio($servicio_precio_utilidad["id_servicio"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_servicio_tipo_cliente->setEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    $precio_servicio_tipo_cliente->setIdServicio($servicio_precio_utilidad["id_servicio"]);
                    $precio_servicio_tipo_cliente->setPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    PrecioServicioTipoClienteDAO::save($precio_servicio_tipo_cliente);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido editar todos los precios para el tipo_cliente ".$id_tipo_cliente." : ".$e);
                throw new Exception("No se han podido editar todos los precios para el tipo_cliente");
            }
            DAO::transEnd();
            Logger::log("Precios editados exitosamente");
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
            Logger::log("ELiminando los precios de servicio para el rol ".$id_rol);
            
            //Se inicializa el registro a eliminar y se elimina
            DAO::transBegin();
            try
            {
                foreach($productos as $producto)
                {
                    $precio_producto_rol = PrecioProductoRolDAO::getByPK($producto, $id_rol);
                    if(is_null($precio_producto_rol))
                        throw new Exception("El rol ".$id_rol." no tiene precio especial para el producto ".$producto);
                    PrecioProductoRolDAO::delete($precio_producto_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron eliminar los precios para producto del rol ".$id_rol." : ".$e);
                throw new Exception("No se pudieron eliminar los precios para producto del rol");
            }
            DAO::transEnd();
            Logger::log("Precios de productos eliminados exitosamente");
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
            Logger::log("ELiminando los precios de servicio para el tipo_cliente ".$id_tipo_cliente);
            
            //Se inicializa el registro a eliminar y se elimina
            DAO::transBegin();
            try
            {
                foreach($productos as $producto)
                {
                    $precio_producto_tipo_cliente = PrecioProductoTipoClienteDAO::getByPK($producto, $id_tipo_cliente);
                    if(is_null($precio_producto_tipo_cliente))
                        throw new Exception("El tipo_cliente ".$id_tipo_cliente." no tiene precio especial para el producto ".$producto);
                    PrecioProductoTipoClienteDAO::delete($precio_producto_tipo_cliente);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron eliminar los precios para producto del tipo_cliente ".$id_tipo_cliente." : ".$e);
                throw new Exception("No se pudieron eliminar los precios para producto del tipo_cliente");
            }
            DAO::transEnd();
            Logger::log("Precios de productos eliminados exitosamente");
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
            Logger::log("ELiminando los precios de servicio para el usuario ".$id_usuario);
            
            //Se inicializa el registro a eliminar y se elimina
            DAO::transBegin();
            try
            {
                foreach($productos as $producto)
                {
                    $precio_producto_usuario = PrecioProductoUsuarioDAO::getByPK($producto, $id_usuario);
                    if(is_null($precio_producto_usuario))
                        throw new Exception("El usuario ".$id_usuario." no tiene precio especial para el producto ".$producto);
                    PrecioProductoUsuarioDAO::delete($precio_producto_usuario);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron eliminar los precios para producto del usuario ".$id_usuario." : ".$e);
                throw new Exception("No se pudieron eliminar los precios para producto del usuario");
            }
            DAO::transEnd();
            Logger::log("Precios de productos eliminados exitosamente");
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
            Logger::log("ELiminando los precios de servicio para el rol ".$id_rol);
            
            //Se inicializa el registro a eliminar y se elimina
            DAO::transBegin();
            try
            {
                foreach($servicios as $servicio)
                {
                    $precio_servicio_rol = PrecioServicioRolDAO::getByPK($servicio, $id_rol);
                    if(is_null($precio_servicio_rol))
                        throw new Exception("El rol ".$id_rol." no tiene precio especial para el servicio ".$servicio);
                    PrecioServicioRolDAO::delete($precio_servicio_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudieron eliminar los precios para servicio del rol ".$id_rol." : ".$e);
                throw new Exception("No se pudieron eliminar los precios para servicio del rol");
            }
            DAO::transEnd();
            Logger::log("Precios de servicios eliminados exitosamente");
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
		$productos_precios_utilidad
	)
	{  
            Logger::log("Registrando precios de los servicios para el rol ".$id_rol);
            
            //valida al rol obtendio
            $validar = self::validarRol($id_rol);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a guardar
            $precio_producto_rol = new PrecioProductoRol( array( "id_rol" => $id_rol ) );
            DAO::transBegin();
            try
            {
                foreach($productos_precios_utilidad as $producto_precio_utilidad)
                {
                    $validar = self::validarProducto($producto_precio_utilidad["id_producto"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($producto_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($producto_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_producto_rol->setEsMargenUtilidad($producto_precio_utilidad["es_margen_utilidad"]);
                    $precio_producto_rol->setIdProducto($producto_precio_utilidad["id_producto"]);
                    $precio_producto_rol->setPrecioUtilidad($producto_precio_utilidad["precio_utilidad"]);
                    PrecioProductoRolDAO::save($precio_producto_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido guardar todos los precios para el rol ".$id_rol." : ".$e);
                throw new Exception("No se han podido guardar todos los precios para el rol");
            }
            DAO::transEnd();
            Logger::log("Precios guardados exitosamente");
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
		$productos_precios_utilidad, 
		$id_rol
	)
	{  
            Logger::log("Registrando precios de los servicios para el rol ".$id_rol);
            
            //valida al rol obtendio
            $validar = self::validarRol($id_rol);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a guardar
            $precio_producto_rol = new PrecioProductoRol( array( "id_rol" => $id_rol ) );
            DAO::transBegin();
            try
            {
                foreach($productos_precios_utilidad as $producto_precio_utilidad)
                {
                    $validar = self::validarProducto($producto_precio_utilidad["id_producto"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($producto_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($producto_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_producto_rol->setEsMargenUtilidad($producto_precio_utilidad["es_margen_utilidad"]);
                    $precio_producto_rol->setIdProducto($producto_precio_utilidad["id_producto"]);
                    $precio_producto_rol->setPrecioUtilidad($producto_precio_utilidad["precio_utilidad"]);
                    PrecioProductoRolDAO::save($precio_producto_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido guardar todos los precios para el rol ".$id_rol." : ".$e);
                throw new Exception("No se han podido guardar todos los precios para el rol");
            }
            DAO::transEnd();
            Logger::log("Precios guardados exitosamente");
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
            Logger::log("Editando precios de los servicios para el rol ".$id_rol);
            
            //valida al rol obtendio
            $validar = self::validarRol($id_rol);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se inicializa el registro a editar. Si alguno de los registros no existe, se guardara
            $precio_servicio_rol = new PrecioServicioRol( array( "id_rol" => $id_rol ) );
            DAO::transBegin();
            try
            {
                foreach($servicios_precios_utilidad as $servicio_precio_utilidad)
                {
                    $validar = self::validarServicio($servicio_precio_utilidad["id_servicio"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $validar = self::validarEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    if(is_string($validar))
                        throw new Exception($validar);
                    
                    $precio_servicio_rol->setEsMargenUtilidad($servicio_precio_utilidad["es_margen_utilidad"]);
                    $precio_servicio_rol->setIdServicio($servicio_precio_utilidad["id_servicio"]);
                    $precio_servicio_rol->setPrecioUtilidad($servicio_precio_utilidad["precio_utilidad"]);
                    PrecioServicioRolDAO::save($precio_servicio_rol);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se han podido editar todos los precios para el rol ".$id_rol." : ".$e);
                throw new Exception("No se han podido editar todos los precios para el rol");
            }
            DAO::transEnd();
            Logger::log("Precios editados exitosamente");
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
