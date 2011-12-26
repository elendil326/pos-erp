<?php
require_once("interfaces/Servicios.interface.php");
/**
  *
  *
  *
  **/
	
  class ServiciosController implements IServicios{
  
      
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
         * Valida los parametros de la tabla productos_orden_de_servicio.
         */
        private static function validarParametrosProductoOrdenDeServicio
        (
                $id_producto = null,
                $precio = null,
                $cantidad = null,
                $descuento = null,
                $impuesto = null,
                $retencion = null,
                $id_unidad = null
        )
        {
            
            //valida que el producto exista en la base de datos
            if(!is_null($id_producto))
            {
                $producto =ProductoDAO::getByPK($id_producto) ;
                if(is_null($producto))
                {
                    return "El producto con id ".$id_producto." no existe";
                }
                
                if(!$producto->getActivo())
                {
                    return "El producto ".$id_producto." no esta activo";
                }
            }
            
            //valida que el precio este en el rango
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio,1.8e200,"precio");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la cantidad este en el rango
            if(!is_null($cantidad))
            {
                $e = self::validarNumero($cantidad, PHP_INT_MAX, "cantidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el descuento este en el rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el impuesto este en rango
            if(!is_null($impuesto))
            {
                $e = self::validarNumero($impuesto, 1.8e200, "impuesto");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la retencion este en rango
            if(!is_null($retencion))
            {
                $e = self::validarNumero($retencion, 1.8e200, "retencion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la unidad exista en la base de datos
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                {
                        return "La unidad con id ".$id_unidad." no existe";
                }
                if(!$unidad->getActiva())
                {
                    return "La unidad ".$id_unidad." no esta activa";
                }
            }
            
            //no se encontro error, regresa true
            return true;
        }
        
        /*
         * Valida los parametros de la tabla clasificacion_servicio. Regresa un string con el error si se encuentra
         * alguno, regresa verdadero en caso contrario
         */
        private static function validarParametrosClasificacionServicio
        (
                $id_clasificacion_servicio = null,
                $nombre = null,
                $garantia = null,
                $descripcion = null,
                $margen_utilidad = null,
                $descuento = null,
                $activa = null
        )
        {
            //valida que la clasificacion exista y este activa
            if(!is_null($id_clasificacion_servicio))
            {
                $clasificacion_servicio = ClasificacionServicioDAO::getByPK($id_clasificacion_servicio);
                if(is_null($clasificacion_servicio))
                    return "La clasificacion servicio ".$id_clasificacion_servicio." no existe";
                
                if(!$clasificacion_servicio->getActiva())
                    return "La clasificacion servicio ".$id_clasificacion_servicio." esta desactivada";
            }
            
            //valida que el nombre este en rango y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 50, "nombre");
                if(is_string($e))
                    return $e;
                $clasificaciones_servicio = ClasificacionServicioDAO::search( new ClasificacionServicio( array("nombre" => trim($nombre)) ) );
                foreach($clasificaciones_servicio as $clasificacion_servicio)
                {
                    if($clasificacion_servicio->getActiva())
                        return "El nombre (".$nombre.") ya esta en uso por la clasificacion ".$clasificacion_servicio->getIdClasificacionServicio();
                }
            }
            
            //valida que la garantia este en rango
            if(!is_null($garantia))
            {
                $e = self::validarNumero($garantia, PHP_INT_MAX, "garantia");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la descripcion este en rango
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el margen de utilidad este en ango
            if(!is_null($margen_utilidad))
            {
                $e = self::validarNumero($margen_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida qe el descuento este en rango
            if(!is_null($descuento))
            {
                $e = self::validarNumero($descuento, 100, "descuento");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activa
            if(!is_null($activa))
            {
                $e = self::validarNumero($activa, 1, "activa");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa true.
            return true;
        }
        
        /*
         * Valida los parametros de la tabla orden_de_servicio. Regres aun string con el error si se ha encontrado
         * alguno. En caso contrario regresa verdadero
         */
        private static function validarParametrosOrdenDeServicio
        (
                $id_orden_de_servicio = null,
                $id_servicio = null,
                $id_usuario_venta = null,
                $descripcion = null,
                $motivo_cancelacion = null,
                $adelanto = null
        )
        {
            //valida que la orden de servicio exista y que este activa
            if(!is_null($id_orden_de_servicio))
            {
                $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden_de_servicio);
                if(is_null($orden_de_servicio))
                {
                    return "La orden de servicio ".$id_orden_de_servicio." no existe";
                }
                if(!$orden_de_servicio->getActiva())
                {
                    return "La orden de servicio ".$id_orden_de_servicio." no esta activa";
                }
            }
            
            //valida que el servicio exista y este activo
            if(!is_null($id_servicio))
            {
                $servicio = ServicioDAO::getByPK($id_servicio);
                if(is_null($servicio))
                    return "El servicio ".$id_servicio." no existe";
                
                if(!$servicio->getActivo())
                    return "El servicio ".$id_servicio." no esta activo";
            }
            
            //valida que el usuario al que se le vende exista 
            if(!is_null($id_usuario_venta))
            {
                $usuario = UsuarioDAO::getByPK($id_usuario_venta);
                if(is_null($usuario))
                    return "EL usuario ".$id_usuario_venta." no existe";
                
                if(!$usuario->getActivo())
                    return "El usuario ".$id_usuario_venta." esta inactivo";
            }
            
            //valida que la descripcion este en rango
            if(!is_null($descripcion))
            {
                $e = self::validarString($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el motivo de cancelacion este en rango
            if(!is_null($motivo_cancelacion))
            {
                $e = self::validarString($motivo_cancelacion, 255, "motivo de cancelacion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el adelanto este en rango
            if(!is_null($adelanto))
            {
                $e = self::validarNumero($adelanto, 1.8e200, "adelanto");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error, regresa verdadero
            return true;
            
        }
        
        /*
         * Validar parametros de la tabla servicio. Regresa un string con el error en caso de
         * encontrarse alguno. En caso contrario regresa verdadero
         */
        private static function validarParametrosServicio
        (
                $id_servicio = null,
                $nombre_servicio = null,
                $metodo_costeo = null,
                $codigo_servicio = null,
                $compra_en_mostrador = null,
                $activo = null,
                $margen_de_utilidad = null,
                $descripcion_servicio = null,
                $costo_estandar = null,
                $garantia = null,
                $control_existencia = null,
                $foto_servicio = null,
                $precio = null
        )
        {
            //valida que el servicio exista y que este activo
            if(!is_null($id_servicio))
            {
                $servicio = ServicioDAO::getByPK($id_servicio);
                if(is_null($servicio))
                    return "El servicio ".$id_servicio." no existe";
                if(!$servicio->getActivo())
                    return "El servicio ".$id_servicio." esta inactivo";
            }
            
            //valida el rango del nombre de servicio y que no se repita
            if(!is_null($nombre_servicio))
            {
                $e = self::validarString($nombre_servicio, 50, "nombre de servicio");
                if(is_string($e))
                    return $e;
                $servicios = ServicioDAO::search( new Servicio( array( "nombre_servicio" => trim($nombre_servicio) ) ) );
                foreach($servicios as $servicio)
                {
                    if($servicio->getActivo())
                        return "El nombre de servicio (".$nombre_servicio.") ya esta en uso por el servicio ".$servicio->getIdServicio();
                }
            }
            
            //valida el metodo de costeo
            if(!is_null($metodo_costeo))
            {
                if($metodo_costeo!="precio" && $metodo_costeo!="margen")
                    return "El metodo de costeo (".$metodo_costeo.") es invalido";
            }
            
            //valida que el codigo de servicio este en rango y que no se repita
            if(!is_null($codigo_servicio))
            {
                $e = self::validarString($codigo_servicio, 20, "codigo de servicio");
                if(is_string($e))
                    return $e;
                $servicios = ServicioDAO::search( new Servicio( array( "codigo_servicio" => $codigo_servicio ) ) );
                foreach($servicios as $servicio)
                {
                    if($servicio->getActivo())
                        return "El codigo de servicio (".$codigo_servicio.") esta siendo usado por el servicio ".$servicio->getIdServicio ();
                }
            }
            
            //valida el boleano compra en mostrador
            if(!is_null($compra_en_mostrador))
            {
                $e = self::validarNumero($compra_en_mostrador, 1, "compra en mostrador");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano activo
            if(!is_null($activo))
            {
                $e = self::validarNumero($activo, 1, "activo");
                if(is_string($e))
                    return $e;
            }
            
            //valida el margen de utilidad
            if(!is_null($margen_de_utilidad))
            {
                $e = self::validarNumero($margen_de_utilidad, 1.8e200, "margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida la descripcion del servicio
            if(!is_null($descripcion_servicio))
            {
                $e = self::validarString($descripcion_servicio, 255, "descripcion de servicio");
                if(is_string($e))
                    return $e;
            }
            
            //valida el costo estandar
            if(!is_null($costo_estandar))
            {
                $e = self::validarNumero($costo_estandar, 1.8e200, "costo estandar");
                if(is_string($e))
                    return $e;
            }
            
            //valida la garantia
            if(!is_null($garantia))
            {
                $e = self::validarNumero($garantia, PHP_INT_MAX, "garantia");
                if(is_string($e))
                    return $e;
            }
            
            //valida el control de existencia
            if(!is_null($control_existencia))
            {
                $e = self::validarNumero($control_existencia, PHP_INT_MAX, "control de existencia");
                if(is_string($e))
                    return $e;
            }
            
            //valida la foto del servicio
            if(!is_null($foto_servicio))
            {
                $e = self::validarString($foto_servicio, 50, "foto del servicio");
                if(is_string($e))
                    return $e;
            }
            
            //valida el precio
            if(!is_null($precio))
            {
                $e = self::validarNumero($precio, 1.8e200, "precio");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error, regresa verdadero
            return true;
        }
        
        /*
         * Valida los parametros de la tabla servicio_empresa. Regresa un string con el error
         * en caso de encontrarse alguno, de lo contrario regresa verdadero
         */
        private static function validarParametrosServicioEmpresa
        (
                $id_empresa = null,
                $precio_utilidad = null,
                $es_margen_utilidad = null
        )
        {
            //valida que la empresa exista y q este activa
            if(!is_null($id_empresa))
            {
                $empresa = EmpresaDAO::getByPK($id_empresa);
                if(is_null($empresa))
                    return "La empresa con id ".$id_empresa." no existe";
                
                if(!$empresa->getActivo())
                    return "La empresa ".$id_empresa." no esta activa";
            }
            
            //valida que el precio_utilidad este en rango
            if(!is_null($precio_utilidad))
            {
                $e = self::validarNumero($precio_utilidad, 1.8e200, "precio utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano es_margen_utilidad
            if(!is_null($es_margen_utilidad))
            {
                $e = self::validarNumero($es_margen_utilidad, 1, "es margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
        }
        
        /*
         * Valida los parametros de la tabla servicio_sucursal. Regresa un string con el error
         * si se encuentra alguno. De lo contrario regresa verdadero
         */
        private static function validarParametrosServicioSucursal
        (
                $id_sucursal = null,
                $precio_utilidad = null,
                $es_margen_utilidad = null
        )
        {
            //valida que la sucursal exista y q este activa
            if(!is_null($id_sucursal))
            {
                $sucursal = SucursalDAO::getByPK($id_sucursal);
                if(is_null($sucursal))
                    return "La sucursal con id ".$id_sucursal." no existe";
                
                if(!$sucursal->getActiva())
                    return "La sucursal ".$id_sucursal." no esta activa";
            }
            
            //valida que el precio_utilidad este en rango
            if(!is_null($precio_utilidad))
            {
                $e = self::validarNumero($precio_utilidad, 1.8e200, "precio utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //valida el boleano es_margen_utilidad
            if(!is_null($es_margen_utilidad))
            {
                $e = self::validarNumero($es_margen_utilidad, 1, "es margen de utilidad");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
        }
        
        
        /*
         * Valida los parametros de la tabla seguimiento. Regresa un string con el error y se encuentra
         * alguno, si no, regresa verdadero
         */
        private static function validarParametrosSeguimiento
        (
                $id_seguimiento_de_servicio = null,
                $id_orden_de_servicio = null,
                $id_localizacion = null,
                $estado = null
        )
        {
            //valida que el seguimiento exista
            if(!is_null($id_seguimiento_de_servicio))
            {
                $seguimiento = SeguimientoDeServicioDAO::getByPK($id_seguimiento_de_servicio);
                if(is_null($seguimiento))
                    return "El seguimiento ".$id_seguimiento_de_servicio." no existe";
            }
            
            //valida que la orden de servicio exista y este activa
            if(!is_null($id_orden_de_servicio))
            {
                $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden_de_servicio);
                if(is_null($orden_de_servicio))
                    return "La orden de servicio ".$id_orden_de_servicio." no existe";
                
                if(!$orden_de_servicio->getActiva())
                    return "La orden de servicio ".$id_orden_de_servicio." esta desactivada";
            }
            
            //valida que la localizacion sea una sucursal valida o sea un -1 para indicar que esta en movimiento
            if(!is_null($id_localizacion))
            {
                $sucursal = SucursalDAO::getByPK($id_localizacion);
                if(is_null($sucursal)&&$id_localizacion!=-1)
                    return "La localizacion ".$id_localizacion." no es valida";
            }
            
            //valida que el estado este en rango
            if(!is_null($estado))
            {
                $e = self::validarString($estado, 255, "estado");
                if(is_string($e))
                    return $e;
            }
            
            //No se encontro error
            return true;
        }
        
        
      
      
      
  
	/**
 	 *
 	 *Edita la informaci?n de una clasificaci?n de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio que se edita
 	 * @param retenciones json Retenciones que afectan a los servicios de esta clasificacion
 	 * @param impuestos json Impuestos que afectan a los servicios de esta clasificacion
 	 * @param descuento float Descuento que aplicara a los servicios de esta clasificacion
 	 * @param margen_utilidad float Margen de utilidad que tendran los servicios de este tipo de servicio
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param garantia int Numero de meses que tiene la garantia de este tipo de servicios
 	 * @param nombre string Nombre de la clasificacion de servicio
 	 **/
	public static function EditarClasificacion
	(
		$id_clasificacion_servicio, 
		$retenciones = null, 
		$impuestos = null, 
		$descuento = null, 
		$margen_utilidad = null, 
		$descripcion = null, 
		$garantia = null, 
		$nombre = null
	)
	{  
            Logger::log("Editando clasificacion de servicio ".$id_clasificacion_servicio);
            
            //se validan los parametros
            $validar = self::validarParametrosClasificacionServicio($id_clasificacion_servicio,$nombre,$garantia,$descripcion,$margen_utilidad,$descuento);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $clasificacion_servicio = ClasificacionServicioDAO::getByPK($id_clasificacion_servicio);
            if(!is_null($descuento))
            {
                $clasificacion_servicio->setDescuento($descuento);
            }
            if(!is_null($margen_utilidad))
            {
                $clasificacion_servicio->setMargenUtilidad($margen_utilidad);
            }
            if(!is_null($descripcion))
            {
                $clasificacion_servicio->setMargenUtilidad($margen_utilidad);
            }
            if(!is_null($garantia))
            {
                $clasificacion_servicio->setDescripcion($garantia);
            }
            if(!is_null($nombre))
            {
                $clasificacion_servicio->setNombre(trim($nombre));
            }
            
            //Se guardan los cambios realizados en la clasificacion de servicio.
            //Si se obtienen impuestos y/o retenciones, se actualizan o guardan los elementos obtenidos
            //de la lista. Despues, se recorren aquellos que hay actualmente y se buscan en la lista obtenida,
            //se eliminana aquellos que no se encuentren en la lista obtenida.
            DAO::transBegin();
            try
            {
                ClasificacionServicioDAO::save($clasificacion_servicio);
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_servicio = new ImpuestoClasificacionServicio(
                            array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto con id ".$impuesto." no existe");
                        $impuesto_clasificacion_servicio->setIdImpuesto($impuesto);
                        ImpuestoClasificacionServicioDAO::save($impuesto_clasificacion_servicio);
                    }
                    $impuestos_clasificacion_servicio = ImpuestoClasificacionServicioDAO::search(
                            new ImpuestoClasificacionServicio( array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) ) );
                    foreach($impuestos_clasificacion_servicio as $i_c_s)
                    {
                        $encontrado = false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto == $i_c_s->getIdImpuesto())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                        {
                            ImpuestoClasificacionServicioDAO::delete($i_c_s);
                        }
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_servicio = new RetencionClasificacionServicio( 
                            array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion con id ".$retencion." no existe");
                        $retencion_clasificacion_servicio->setIdRetencion($retencion);
                        RetencionClasificacionServicioDAO::save($retencion_clasificacion_servicio);
                    }
                    $retenciones_clasificacion_servicio = RetencionClasificacionServicioDAO::search(
                            new RetencionClasificacionServicio( array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) ) );
                    foreach($retenciones_clasificacion_servicio as $r_c_s)
                    {
                        $encontrado = false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion == $r_c_s->getIdRetencion())
                                $encontrado = true;
                        }
                        if(!$encontrado)
                        {
                            RetencionClasificacionServicioDAO::delete($r_c_s);
                        }
                    }
                }/* Fin if de retenciones */
            }/* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la clasificacion de servicio ".$id_clasificacion_servicio." : ".$e);
                throw new Exception("No se pudo editar la clasificacion de servicio ");
            }
            DAO::transEnd();
            Logger::log("Clasificacion de servicio editado exitosamente");
	}
  
	/**
 	 *
 	 *Elimina una clasificacion de servicio
 	 *
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio
 	 **/
	public static function EliminarClasificacion
	(
		$id_clasificacion_servicio
	)
	{  
            Logger::log("Eliminando clasificacion de servicio ".$id_clasificacion_servicio);
            
            //valida el parametro
            $validar = self::validarParametrosClasificacionServicio($id_clasificacion_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se eliminaran los registros de impuesto_clasificacion_servicio y retencion_clasificacion_servicio que contengan 
            //a esta clasificaicon
            $clasificacion_servicio = ClasificacionServicioDAO::getByPK($id_clasificacion_servicio);
            $clasificacion_servicio->setActiva(0);
            
            $impuestos_clasificacion_servicio = ImpuestoClasificacionServicioDAO::search( 
                    new ImpuestoClasificacionServicio( array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) ) );
            
            $retenciones_clasificacion_servicio = RetencionClasificacionServicioDAO::search(
                    new RetencionClasificacionServicio( array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) ) );
            
            DAO::transBegin();
            try
            {
                ClasificacionServicioDAO::save($clasificacion_servicio);
                foreach($impuestos_clasificacion_servicio as $impuesto_clasificacion_servicio)
                {
                    ImpuestoClasificacionServicioDAO::delete($impuesto_clasificacion_servicio);
                }
                foreach($retenciones_clasificacion_servicio as $retencion_clasificacion_servicio)
                {
                    RetencionClasificacionServicioDAO::delete($retencion_clasificacion_servicio);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar la clasificacion del servicio ".$id_clasificacion_servicio." : ".$e);
                throw new Exception("No se pudo eliminar la clasificacion del servicio");
            }
            DAO::transEnd();
            Logger::log("Se elimino la clasificacion de servicio exitosamente");
	}
  
	/**
 	 *
 	 *Cancela una orden de servicio. Cuando se cancela un servicio, se cancelan tambien las ventas en las que aparece este servicio.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden del servicio a cancelar
 	 * @param motivo_cancelacion string Motivo de la cancelacion
 	 **/
	public static function CancelarOrden
	(
		$id_orden_de_servicio, 
		$motivo_cancelacion = null
	)
	{  
            Logger::log("Cancelando orden de servicio ".$id_orden_de_servicio);
            
            //valida los parametros recibidos
            $validar = self::validarParametrosOrdenDeServicio($id_orden_de_servicio, null, null, null, $motivo_cancelacion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden_de_servicio);
            $orden_de_servicio->setCancelada(1);
            $orden_de_servicio->setActiva(0);
            $orden_de_servicio->setMotivoCancelacion($motivo_cancelacion);
            
            DAO::transBegin();
            try
            {
                OrdenDeServicioDAO::save($orden_de_servicio);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo cancela la orden de servicio ".$id_orden_de_servicio." : ".$e);
                throw new Exception("No se pudo cancela la orden de servicio ".$id_orden_de_servicio);
            }
            DAO::transEnd();    
            Logger::log("Orden de servicio cancelada exitosamente");
	}
  
	/**
 	 *
 	 *Genera una nueva clasificacion de servicio
 	 *
 	 * @param nombre string Nombre de la clasificacion del servicio
 	 * @param garantia int numero de meses de garantia que tienen los servicios de esta clasificacion
 	 * @param descripcion string Descripcion de la clasificacion del servicio
 	 * @param margen_utilidad float Margen de utilidad que se le ganara a los servicios de este tipo
 	 * @param descuento float Descuento que aplicara a los servicios de este tipo
 	 * @param activa bool Si esta clasificacion sera activa al momento de ser creada
 	 * @param impuestos json Impuestos que afectan a este tipo de servicio
 	 * @param retenciones json Retenciones que afectana este tipo de servicio
 	 * @return id_clasificacion_servicio int Id de la clasificacion que se creo
 	 **/
	public static function NuevaClasificacion
	(
		$nombre, 
		$garantia = null, 
		$descripcion = null, 
		$margen_utilidad = null, 
		$descuento = null, 
		$activa = 1, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
            Logger::log("Creando nueva clasificacion de servicio");
            
            //se validan los parametros obtendios
            $validar = self::validarParametrosClasificacionServicio(null,$nombre,$garantia,$descripcion,$margen_utilidad,$descuento,$activa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            if(is_null($activa))
                $activa=1;
            //se inicializa el registro de clasificacion servicio
            $clasificacion_servicio = new ClasificacionServicio( array( 
                                            "nombre"            => trim($nombre),
                                            "garantia"          => $garantia,
                                            "descripcion"       => $descripcion,
                                            "margen_utilidad"   => $margen_utilidad,
                                            "descuento"         => $descuento,
                                            "activa"            => $activa
                                                        )
                                                    );
            
            //Se guarda el registro. Si se recibieron impuestos y/o retenciones 
            //se crean los registros y se guardan
            DAO::transBegin();
            try
            {
                ClasificacionServicioDAO::save($clasificacion_servicio);
                
                if(!is_null($impuestos))
                {
                    $impuesto_clasificacion_servicio = new ImpuestoClasificacionServicio(
                            array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto con id ".$impuesto." no existe");
                        $impuesto_clasificacion_servicio->setIdImpuesto($impuesto);
                        ImpuestoClasificacionServicioDAO::save($impuesto_clasificacion_servicio);
                    }
                }/* Fin if de impuestos*/
                if(!is_null($retenciones))
                {
                    $retencion_clasificacion_servicio = new RetencionClasificacionServicio( 
                            array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion con id ".$retencion." no existe");
                        $retencion_clasificacion_servicio->setIdRetencion($retencion);
                        RetencionClasificacionServicioDAO::save($retencion_clasificacion_servicio);
                    }
                }/* Fin if de retenciones */
            }/* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva clasificacion de servicio: ".$e);
                throw new Exception("No se pudo crear la nueva clasificacion de servicio");
            }
            DAO::transEnd();
            Logger::log("Clasificacion de servicio creada exitosamente");
            return array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() );
	}
  
	/**
 	 *
 	 *Lista todos los servicios de la instancia. Puede filtrarse por empresa, por sucursal o por activo y puede ordenarse por sus atributos.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus servicios
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus servicios
 	 * @param activo bool Si este valor no es obtenido, se mostraran los servicios tanto activos como inactivos. Si es true, se mostraran solo los activos, si es false se mostraran solo los inactivos.
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @return servicios json Objeto que contendra la lista de servicios
 	 **/
	public static function Lista
	(
		$id_empresa = null, 
		$id_sucursal = null, 
		$activo = null, 
		$orden = null
	)
	{  
            Logger::log("Listando servicios");
            
            //valida el parametro orden
            if(!is_null($orden))
            {
                if
                (
                        $orden != "id_servicio"         &&
                        $orden != "nombre_servicio"     &&
                        $orden != "metodo_costeo"       &&
                        $orden != "codigo_servicio"     &&
                        $orden != "compra_en_mostrador" &&
                        $orden != "activo"              &&
                        $orden != "margen_de_utilidad"  &&
                        $orden != "descripcion_servicio"&&
                        $orden != "costo_estandar"      &&
                        $orden != "garantia"            &&
                        $orden != "control_existencia"  &&
                        $orden != "foto_servicio"       &&
                        $orden != "precio"
                )
                {
                    Logger::error("La variable orden (".$orden.") no es valida");
                    throw new Exception("La variable orden (".$orden.") no es valida");
                }
            }/* Fin if de orden */
            
            /*
             * A continuacion, se validan los demas parametros. Si mas de uno fue recibido,
             * se realiza una interseccion entre sus busquedas separadas. Los parametros que no se
             * reciban traeran a todos los elementos o seran igual a alguno anterior para mantener las propiedades 
             * de la interseccion.
             */
            $servicio_criterio_1 = array();
            $servicio_criterio_2 = array();
            $servicio_criterio_3 = array();
            
            if(!is_null($activo))
            {
                $servicio_criterio_1 = ServicioDAO::search( new Servicio( array( "activo" => $activo ) ) , $orden);
            }
            else
            {
                $servicio_criterio_1 = ServicioDAO::getAll(null,null,$orden);
            }
            
            if(!is_null($id_empresa))
            {
                $servicios_empresa = ServicioEmpresaDAO::search( new ServicioEmpresa( array( "id_empresa" => $id_empresa ) ) );
                foreach($servicios_empresa as $servicio_empresa)
                {
                    array_push($servicio_criterio_2,  ServicioDAO::getByPK($servicio_empresa->getIdServicio()));
                }
            }
            else
            {
                $servicio_criterio_2 = $servicio_criterio_1;
            }
            
            if(!is_null($id_sucursal))
            {
                $servicios_sucursal = ServicioSucursalDAO::search( new ServicioSucursal( array( "id_sucursal" => $id_sucursal ) ) );
                foreach($servicios_sucursal as $servicio_sucursal)
                {
                    array_push($servicio_criterio_3, ServicioDAO::getByPK($servicio_sucursal->getIdServicio()));
                }
            }
            else
            {
                $servicio_criterio_3 = $servicio_criterio_2;
            }
            
            $servicios = array_intersect($servicio_criterio_1, $servicio_criterio_2, $servicio_criterio_3);
            Logger::log("Lista recuperada exitosamente con ".count($servicios)." elementos");
            return $servicios;
	}
  
	/**
 	 *
 	 *Crear un nuevo concepto de servicio.
 	 *
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 * @param metodo_costeo string Mtodo de costeo del servicio: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param empresas json Objeto que contiene los ids de las empresas a las que pertenece este servicio
 	 * @param compra_en_mostrador bool Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param sucursales json Sucursales en las que estara disponible este servicio
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param retenciones json Ids de las retenciones que afectan este servicio
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param activo bool Si queremos que este activo o no mientras lo insertamos
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario   Array
 	 * @param margen_de_utilidad float Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_servicio string La url de la foto del servicio
 	 * @return id_servicio int Id del servicio creado
 	 **/
	public static function Nuevo
	(
		$codigo_servicio, 
		$metodo_costeo, 
		$compra_en_mostrador, 
		$costo_estandar, 
		$nombre_servicio, 
		$precio = null, 
		$retenciones = null, 
		$garantia = null, 
		$foto_servicio = null, 
		$control_de_existencia = null, 
		$descripcion_servicio = null, 
		$activo = true, 
		$clasificaciones = null, 
		$impuestos = null, 
		$margen_de_utilidad = null
	)
	{  
            Logger::log("Creando nuevo servicio");
            
            //se validan los parametros recibidos
            $validar = self::validarParametrosServicio(null,$nombre_servicio,$metodo_costeo,
                    $codigo_servicio,$compra_en_mostrador,$activo,$margen_de_utilidad,$descripcion_servicio,
                    $costo_estandar,$garantia,$control_de_existencia,$foto_servicio,$precio);

            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //valida que se haya recibido el parametro esperado por el metodo de costeo
            if( ( $metodo_costeo == "precio" && is_null($precio) ) || ( $metodo_costeo == "margen" && is_null($margen_de_utilidad) ) )
            {
                Logger::error("No se recibio el parametro correspondiente al metodo de costeo (".$metodo_costeo.")");
                throw new Exception("No se recibio el parametro correspondiente al metodo de costeo (".$metodo_costeo.")");
            }
            
            if(is_null($activo))
                $activo = 1;
            
            //Se inicializa el registro de servicio
            $servicio = new Servicio( array( 
                                "costo_estandar"            => $costo_estandar,
                                "metodo_costeo"             => $metodo_costeo,
                                "nombre_servicio"           => trim($nombre_servicio),
                                "codigo_servicio"           => trim($codigo_servicio),
                                "compra_en_mostrador"       => $compra_en_mostrador,
                                "activo"                    => $activo,
                                "margen_de_utilidad"        => $margen_de_utilidad,
                                "descripcion_de_servicio"   => $descripcion_servicio,
                                "costo_estandar"            => $costo_estandar,
                                "garantia"                  => $garantia,
                                "control_existencia"        => $control_de_existencia,
                                "foto_servicio"             => $foto_servicio,
                                "precio"                    => $precio
                                    ) 
                                        );
            
            //Se guarda el registro. Si se reciben empresas, sucursales, impuestos y/o retenciones, se guardan 
            //los respectivos registros con la informacion obtenida
            
            DAO::transBegin();
            try
            {
                ServicioDAO::save($servicio);
//                if(!is_null($empresas))
//                {
//                    $servicio_empresa = new ServicioEmpresa( array( "id_servicio" => $servicio->getIdServicio() ) );
//                    foreach($empresas as $empresa)
//                    {
//                        $validar = self::validarParametrosServicioEmpresa($empresa["id_empresa"],$empresa["precio_utilidad"],$empresa["es_margen_utilidad"]);
//                        if(is_string($validar))
//                            throw new Exception($validar);
//                        $servicio_empresa->setIdEmpresa($empresa["id_empresa"]);
//                        $servicio_empresa->setPrecioUtilidad($empresa["precio_utilidad"]);
//                        $servicio_empresa->setEsMargenUtilidad($empresa["es_margen_utilidad"]);
//                        ServicioEmpresaDAO::save($servicio_empresa);
//                    }
//                }/* Fin if de empresas */
//                if(!is_null($sucursales))
//                {
//                    $servicio_sucursal = new ServicioSucursal( array( "id_servicio" => $servicio->getIdServicio() ) );
//                    foreach($sucursales as $sucursal)
//                    {
//                        $validar = self::validarParametrosServicioSucursal($sucursal["id_sucursal"],$sucursal["precio_utilidad"],$sucursal["es_margen_utilidad"]);
//                        if(is_string($validar))
//                            throw new Exception($validar);
//                        $servicio_sucursal->setIdSucursal($sucursal["id_sucursal"]);
//                        $servicio_sucursal->setPrecioUtilidad($sucursal["precio_utilidad"]);
//                        $servicio_sucursal->setEsMargenUtilidad($sucursal["es_margen_utilidad"]);
//                        ServicioSucursalDAO::save($servicio_sucursal);
//                    }
//                }/* Fin if de sucursales */
                if(!is_null($clasificaciones))
                {
                    $servicio_clasificacion = new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($clasificaciones as $clasificacion)
                    {
                        if(is_null(ClasificacionServicioDAO::getByPK($clasificacion)))
                                throw new Exception("La clasificacion ".$clasificacion." no existe");
                        $servicio_clasificacion->setIdClasificacionServicio($clasificacion);
                        ServicioClasificacionDAO::save($servicio_clasificacion);
                    }
                }/* Fin if de clasificaciones */
                if(!is_null($impuestos))
                {
                    $impuesto_servicio = new ImpuestoServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto ".$impuesto." no existe");
                        $impuesto_servicio->setIdImpuesto($impuesto);
                        ImpuestoServicioDAO::save($impuesto_servicio);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_servicio = new RetencionServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe");
                        $retencion_servicio->setIdRetencion($retencion);
                        RetencionServicioDAO::save($retencion_servicio);
                    }
                }/* Fin if de impuestos */
            }/* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo servicio: ".$e);
                throw new Exception("No se pudo crear el nuevo servicio");
            }
            DAO::transEnd();
            Logger::log("Servicio creado exitosamente");
            return array( "id_servicio" => $servicio->getIdServicio() );
            
	}
  
	/**
 	 *
 	 *Edita un servicio
 	 *
 	 * @param id_servicio int Id del servicio a editar
 	 * @param sucursales json Sucursales en las cuales estara disponible este servicio
 	 * @param nombre_servicio string Nombre del servicio
 	 * @param garantia int Si este servicio tiene una garanta en meses.
 	 * @param impuestos json array de ids de impuestos que tiene este servico
 	 * @param metodo_costeo string Mtodo de costeo del servicio: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
 	 * @param empresas string Objeto que contiene los ids de las empresas a las que pertenece este servicio
 	 * @param codigo_servicio string Codigo de control del servicio manejado por la empresa, no se puede repetir
 	 * @param descripcion_servicio string Descripcion del servicio
 	 * @param compra_en_mostrador string Verdadero si este servicio se puede comprar en mostrador, para aquello de compra-venta. Para poder hacer esto, el sistema debe poder hacer compras en mostrador
 	 * @param activo bool Si el servicio esta activo o no
 	 * @param control_de_existencia int 00000001 = Unidades. 00000010 = Caractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = LoteCaractersticas. 00000100 = Series. 00001000 = Pedimentos. 00010000 = Lote
 	 * @param foto_servicio string Url de la foto del servicio
 	 * @param margen_de_utilidad string Un porcentage de 0 a 100 si queremos que este servicio marque utilidad en especifico
 	 * @param clasificaciones json Uno o varios id_clasificacion de este servicio, esta clasificacion esta dada por el usuario Array
 	 * @param retenciones json Ids de retenciones que afectan este servicio
 	 * @param costo_estandar float Valor del costo estandar del servicio
 	 **/
	public static function Editar
	(
		$id_servicio, 
		$costo_estandar = null, 
		$retenciones = null, 
		$sucursales = null, 
		$clasificaciones = null, 
		$garantia = null, 
		$impuestos = null, 
		$nombre_servicio = null, 
		$metodo_costeo = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$codigo_servicio = null, 
		$compra_en_mostrador = null, 
		$control_de_existencia = null, 
		$foto_servicio = null, 
		$margen_de_utilidad = null, 
		$precio = null
	)
	{  
            Logger::log("Editando servicio ".$id_servicio);
            
            //valida los parametros recibidos
            $validar = self::validarParametrosServicio($id_servicio,$nombre_servicio,
                    $metodo_costeo,$codigo_servicio,$compra_en_mostrador,null,
                    $margen_de_utilidad,$descripcion_servicio,$costo_estandar,$garantia,
                    $control_de_existencia,$foto_servicio,$precio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $servicio = ServicioDAO::getByPK($id_servicio);
            if(!is_null($nombre_servicio))
            {
                $servicio->setNombreServicio(trim($nombre_servicio));
            }
            if(!is_null($garantia))
            {
                $servicio->setGarantia($garantia);
            }
            if(!is_null($metodo_costeo))
            {
                $servicio->setMetodoCosteo($metodo_costeo);
            }
            if(!is_null($codigo_servicio))
            {
                $servicio->setCodigoServicio(trim($codigo_servicio));
            }
            if(!is_null($descripcion_servicio))
            {
                $servicio->setDescripcionServicio($descripcion_servicio);
            }
            if(!is_null($compra_en_mostrador))
            {
                $servicio->setCompraEnMostrador($compra_en_mostrador);
            }
            if(!is_null($control_de_existencia))
            {
                $servicio->setControlExistencia($control_de_existencia);
            }
            if(!is_null($foto_servicio))
            {
                $servicio->setFotoServicio($foto_servicio);
            }
            if(!is_null($margen_de_utilidad))
            {
                $servicio->setMargenDeUtilidad($margen_de_utilidad);
            }
            if(!is_null($costo_estandar))
            {
                $servicio->setCostoEstandar($costo_estandar);
            }
            if(!is_null($precio))
            {
                $servicio->setPrecio($precio);
            }
            
            //Se verifica que se cuente con el atributo que busca el metodo de costeo
            if( ( $servicio->getMetodoCosteo() == "precio" && is_null($servicio->getPrecio()) ) || $servicio->getMetodoCosteo() == "margen" && is_null($servicio->getMargenDeUtilidad()) )
            {
                Logger::error("No se cuenta con el parametro ".$metodo_costeo);
                throw new Exception("No se cuenta con el parametro ".$metodo_costeo);
            }
            
            //Se actualiza el registro de servicio. Si se reciben listas de empresas, sucursales, clasificaciones, impuestos
            //y/o retenciones, se recorre la lista y se guardan o actualizan los que se encuentren.
            //Despues se recorren los registros acutales y se buscan en las listas recibidas, si no son encontrados son eliminados
            //de la base de datos
            DAO::transBegin();
            try
            {
                ServicioDAO::save($servicio);
                if(!is_null($empresas))
                {
                    $servicio_empresa = new ServicioEmpresa( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($empresas as $empresa)
                    {
                        $validar = self::validarParametrosServicioEmpresa($empresa["id_empresa"],$empresa["precio_utilidad"],$empresa["es_margen_utilidad"]);
                        if(is_string($validar))
                            throw new Exception($validar);
                        $servicio_empresa->setIdEmpresa($empresa["id_empresa"]);
                        $servicio_empresa->setPrecioUtilidad($empresa["precio_utilidad"]);
                        $servicio_empresa->setEsMargenUtilidad($empresa["es_margen_utilidad"]);
                        ServicioEmpresaDAO::save($servicio_empresa);
                    }
                    $servicios_empresa = ServicioEmpresaDAO::search( new ServicioEmpresa( array( "id_servicio" => $id_servicio ) ) );
                    foreach($servicios_empresa as $s_e)
                    {
                        $encontrado = false;
                        foreach($empresas as $empresa)
                        {
                            if($empresa["id_empresa"] == $s_e->getIdEmpresa())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ServicioEmpresaDAO::delete ($s_e);
                    }
                }/* Fin if de empresas */
                if(!is_null($sucursales))
                {
                    $servicio_sucursal = new ServicioSucursal( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($sucursales as $sucursal)
                    {
                        $validar = self::validarParametrosServicioSucursal($sucursal["id_sucursal"],$sucursal["precio_utilidad"],$sucursal["es_margen_utilidad"]);
                        if(is_string($validar))
                            throw new Exception($validar);
                        $servicio_sucursal->setIdSucursal($sucursal["id_sucursal"]);
                        $servicio_sucursal->setPrecioUtilidad($sucursal["precio_utilidad"]);
                        $servicio_sucursal->setEsMargenUtilidad($sucursal["es_margen_utilidad"]);
                        ServicioSucursalDAO::save($servicio_sucursal);
                    }
                    $servicios_sucursal = ServicioSucursalDAO::search( new ServicioSucursal( array( "id_servicio" => $id_servicio ) ) );
                    foreach($servicios_sucursal as $s_s)
                    {
                        $encontrado = false;
                        foreach($sucursales as $sucursal)
                        {
                            if($sucursal["id_sucursal"] == $s_s->getIdSucursal())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ServicioSucursalDAO::delete ($s_s);
                    }
                }/* Fin if de sucursales */
                if(!is_null($clasificaciones))
                {
                    $servicio_clasificacion = new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($clasificaciones as $clasificacion)
                    {
                        if(is_null(ClasificacionServicioDAO::getByPK($clasificacion)))
                                throw new Exception("La clasificacion ".$clasificacion." no existe");
                        $servicio_clasificacion->setIdClasificacionServicio($clasificacion);
                        ServicioClasificacionDAO::save($servicio_clasificacion);
                    }
                    $servicios_clasificacion = ServicioClasificacionDAO::search( new ServicioClasificacion( array( "id_servicio" => $id_servicio ) ) );
                    foreach($servicios_clasificacion as $s_c)
                    {
                        $encontrado = false;
                        foreach($clasificaciones as $clasificacion)
                        {
                            if($clasificacion == $s_c->getIdClasificacionServicio())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ServicioClasificacionDAO::delete ($s_c);
                    }
                }/* Fin if de clasificaciones */
                if(!is_null($impuestos))
                {
                    $impuesto_servicio = new ImpuestoServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto ".$impuesto." no existe");
                        $impuesto_servicio->setIdImpuesto($impuesto);
                        ImpuestoServicioDAO::save($impuesto_servicio);
                    }
                    $impuesto_servicio = ImpuestoServicioDAO::search( new ImpuestoServicio( array( "id_servicio" => $id_servicio ) ) );
                    foreach($impuesto_servicio as $i_s)
                    {
                        $encontrado = false;
                        foreach($impuestos as $impuesto)
                        {
                            if($impuesto == $i_s->getIdImpuesto())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ImpuestoServicioDAO::delete ($i_s);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    $retencion_servicio = new RetencionServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe");
                        $retencion_servicio->setIdRetencion($retencion);
                        RetencionServicioDAO::save($retencion_servicio);
                    }
                    $retencion_servicio = RetencionServicioDAO::search( new RetencionServicio( array( "id_servicio" => $id_servicio ) ) );
                    foreach($retencion_servicio as $r_s)
                    {
                        $encontrado = false;
                        foreach($retenciones as $retencion)
                        {
                            if($retencion == $r_s->getIdRetencion())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            RetencionServicioDAO::delete ($r_s);
                    }
                }/* Fin if de impuestos */
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el servicio ".$id_servicio." : ".$e);
                throw new Exception("No se pudo editar el servicio");
            }
            DAO::transEnd();
            Logger::log("Servicio editado exitosamente");
	}
  
	/**
 	 *
 	 *Lista de todos las ordenes, se puede filtrar por id_sucursal id_empresa fecha_desde fecha_hasta estado Este metodo se puede utilizar para decirle a un cliente cuando le tocara un servicio en caso de haber mas ordenes en espera.
 	 *
 	 * @param id_empresa int Id de la empresa de la cual se listaran sus ordenes
 	 * @param id_sucursal int Id de la sucursal de la cual se listaran sus ordenes
 	 * @param fecha_desde string Fecha en que se realizo la orden
 	 * @param fecha_hasta string fecha en que se entregara una orden
 	 * @return ordenes json Objeto que contendr� las ordenes.
 	 **/
	public static function ListaOrden
	(
		$id_servicio = null, 
		$fecha_hasta = null, 
		$fecha_desde = null, 
		$id_usuario_venta = null, 
		$activa = null, 
		$cancelada = null
	)
	{  
            Logger::log("listando las ordenes");
            
            //se valida si se recibieron paametros o no para saber que metodo usar
            $parametros = false;
            if
            (
                    !is_null($fecha_hasta)      ||
                    !is_null($fecha_desde)      ||
                    !is_null($id_servicio)      ||
                    !is_null($id_usuario_venta) ||
                    !is_null($activa)           ||
                    !is_null($cancelada)        
            )
                $parametros = true;
            $ordenes = array();
            if($parametros)
            {
                //Si se reciben parametros, se usan dos objetos para comparar las fechas, el primero alamcena los demas parametros
                //y el otro almacena limites de fechas para traer solo las ordenes en rango
                $orden_criterio_1 = new OrdenDeServicio(
                        array(
                            "id_servicio"       => $id_servicio,
                            "id_usuario_venta"  => $id_usuario_venta,
                            "activa"            => $activa,
                            "cancelada"         => $cancelada
                        )
                        );
                $orden_criterio_2 = new OrdenDeServicio();
                if(!is_null($fecha_desde))
                {
                    $orden_criterio_1->setFechaOrden($fecha_desde);
                    if(!is_null($fecha_hasta))
                        $orden_criterio_2->setFechaOrden ($fecha_hasta);
                    else
                        $orden_criterio_2->setFechaOrden (date("Y-m-d H:i:s"));
                }
                else if(!is_null($fecha_hasta))
                {
                    $orden_criterio_1->setFechaOrden($fecha_hasta);
                    $orden_criterio_2->setFechaOrden("1000-01-01 00:00:01");
                }
                $ordenes = OrdenDeServicioDAO::byRange($orden_criterio_1, $orden_criterio_2);
            }
            else
            {
                $ordenes = OrdenDeServicioDAO::getAll();
            }
            Logger::log("Lista de ordenes traida exitosamente con ".count($ordenes)." elementos");
            return $ordenes;
	}
  
	/**
 	 *
 	 *Ver los detalles de una orden de servicio. Puede ordenarse por sus atributos. Los detalles de la orden de servicio son los seguimientos que tiene esa orden as? como el estado y sus fechas de orden y de entrega.
 	 *
 	 * @param id_orden int Id de la orden a revisar
 	 * @return detalle_orden json Objeto que contendra el detalle de la orden
 	 **/
	public static function DetalleOrden
	(
		$id_orden
	)
	{  
            Logger::log("Obtienendo detalles de orden");
            
            //Se valida que exista la orden de servicio
            $orden = OrdenDeServicioDAO::getByPK($id_orden);
            if(is_null($orden))
            {
                Logger::error("La orden de servicio ".$id_orden." no existe");
                throw new Exception("La orden de servicio ".$id_orden." no existe");
            }
            
            //Se crea el arreglo de detalle, donde el primer elemento sera la orden en si,
            //el siguiente seran todos los seguimientos que se le han dado y
            //el ultimo seran los gastos que ha generado
            $detalle_orden = array();
            array_push($detalle_orden,$orden);
            
            array_push($detalle_orden, SeguimientoDeServicioDAO::search( new SeguimientoDeServicio( array( "id_orden_de_servicio" => $id_orden ) ) ));
            
            array_push($detalle_orden, GastoDAO::search( new Gasto( array( "id_orden_de_servicio" => $id_orden ) ) ));
            
            Logger::log("Se obtuvo el detalle de la orden con ".count($detalle_orden[1])." seguimientos y ".count($detalle_orden[2])." gastos");
            
            return $detalle_orden;
            
	}
  
	/**
 	 *
 	 *Una nueva orden de servicio a prestar. Este debe ser un servicio activo. Y prestable desde la sucursal desde donde se inicio la llamada. Los conceptos a llenar estan definidos por el concepto. Se guardara el id del agente que inicio la orden y el id del cliente. La fecha se tomara del servidor.
 	 *
 	 * @param id_cliente int Id del cliente que contrata el servicio
 	 * @param id_servicio int Id del servicio que se contrata
 	 * @param fecha_entrega string Fecha en que se entregara el servicio.
 	 * @param descripcion string Descripcion de la orden o el porque del servicio
 	 * @param adelanto float Adelanto de la orden
 	 * @return id_orden int Id de la orden que se creo.
 	 **/
	public static function NuevaOrden
	(
		$descripcion, 
		$id_servicio, 
		$id_cliente, 
		$fecha_entrega, 
		$adelanto = null
	)
	{  
            Logger::log("Creando nueva orden de servicio");
            
            //Se obtiene al usuario de la sesion actual
            $id_usuario = SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("No se ha podido obtener al usuario de la sesion. Ya inicio sesion?");
                throw new Exception("No se ha podido obtener al usuario de la sesion. Ya inicio sesion?");
            }
            
            //Valida que los datos sean correctos
            $validar = self::validarParametrosOrdenDeServicio(null, $id_servicio, $id_cliente, $descripcion, null, $adelanto);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Si no se recibe adelanto se toma como cero
            if(is_null($adelanto))
                $adelanto=0;
            
            //Se calcula el precio de la orden de servicio
            
            $prec = 0;
            
            $servicio = ServicioDAO::getByPK($id_servicio);
            
            $p = false; //bandera para precio
            $m = false; //bandera para margen
            
            $precio = 0;
            $margen = 0;
            
            //Se verifica si existe un precio especial para este usuario con este servicio, o para el tipo de usuario.
            $precio_servicio = PrecioServicioUsuarioDAO::getByPK($id_servicio, $id_ciente);
            
            if(is_null($precio_servicio))
            {
                $usuario = UsuarioDAO::getByPK($id_cliente);
                $precio_servicio = PrecioServicioTipoClienteDAO::getByPK($id_servicio, $usuario->getIdClasificacionCliente());
                
                if(is_null($precio_servicio))
                {
                    //Si no hay precio especial, se toma el del servicio
                    if($servicio->getMetodoCosteo()=="precio")
                    {
                        $p = true;
                        $precio += $servicio->getPrecio();
                    }
                    else
                    {
                        $m = true;
                        $margen += $servicio->getMargenDeUtilidad();
                    }
                }
                else
                {
                    if($precio_servicio->getEsMargenUtilidad())
                    {
                        $m = true;
                        $margen +=$precio_servicio->getPrecioUtilidad();
                    }
                    else
                    {
                        $p = true;
                        $precio +=$precio_servicio->getPrecioUtilidad();
                    }
                }
            }
            else
            {
                if($precio_servicio->getEsMargenUtilidad())
                {
                    $m = true;
                    $margen +=$precio_servicio->getPrecioUtilidad();
                }
                else
                {
                    $p = true;
                    $precio +=$precio_servicio->getPrecioUtilidad();
                }
            }
            
            //Si el servicio esta en terminos de precio, se toma el precio,
            //si es un margen, se multiplica el margen por su costo y se suma su costo.
            if($p)
            {
                $prec+=$precio;
            }
            else
            {
                $prec+=($margen/100)*($servicio->getCostoEstandar())+$servicio->getCostoEstandar();
            }
            
            //Se inicializa el registro de orden de servicio
            $orden_de_servicio = new OrdenDeServicio( array( 
                                                            
                                                            "id_servicio"       => $id_servicio,
                                                            "id_usuario_venta"  => $id_cliente,
                                                            "id_usuario"        => $id_usuario,
                                                            "fecha_orden"       => date("Y-m-d H:i:s"),
                                                            "fecha_entrega"     => $fecha_entrega,
                                                            "activa"            => 1,
                                                            "cancelada"         => 0,
                                                            "descripcion"       => $descripcion,
                                                            "adelanto"          => $adelanto,
                                                            "precio"            => $prec
                                                            
                                                            )
                                                    );
            
            DAO::transBegin();
            try
            {
                OrdenDeServicioDAO::save($orden_de_servicio);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva orden de servicio ".$e);
                throw new Exception("No se pudo crear la nueva orden de servicio");
            }
            DAO::transEnd();
            Logger::log("Orden de servicio creada exitosamente");
            return array( "id_orden" => $orden_de_servicio->getIdOrdenDeServicio() );
            
	}
  
	/**
 	 *
 	 *Realizar un seguimiento a una orden de servicio existente. Puede usarse para agregar detalles a una orden pero no para editar detalles previos. Puede ser que se haya hecho un abono
 	 *
 	 * @param estado string Estado en el que se encuentra actualmente la orden
 	 * @param id_localizacion int Id de la sucursal en la que se encuentra actualmente la orden, se usara un -1 para indicar que esta en movimiento
 	 * @param id_orden_de_servicio int Id de la orden a darle seguimiento
 	 **/
	public static function SeguimientoOrden
	(
		$estado, 
		$id_localizacion, 
		$id_orden_de_servicio
	)
	{  
            Logger::log("Creando nuevo seguimiento de orden");
            
            //Se obtiene al usuario de la sesion
            $id_usuario = SesionController::getCurrentUser();
            if(is_null($id_usuario))
            {
                Logger::error("El usuario no pudo ser obtenido de la sesion. Ya inicio sesion?");
                throw new Exception("El usuario no pudo ser obtenido de la sesion. Ya inicio sesion?");
            }
            
            //Se obtiene la sucursal de la sesion
            $id_sucural = self::getSucursal();
            if(is_null($id_sucural))
            {
                Logger::error("La sucursal no pudo ser obtenida de la sesion");
                throw new Exception("La sucursal no pudo ser obtenida de la seion");
            }
            
            //Se validan los parametros recibidos
            $validar = self::validarParametrosSeguimiento(null, $id_orden_de_servicio, $id_localizacion, $estado);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $seguimiento_de_servicio = new SeguimientoDeServicio( array(  
            
                                                                        "id_localizacion"       => $id_localizacion,
                                                                        "id_orden_de_servicio"  => $id_orden_de_servicio,
                                                                        "estado"                => $estado,
                                                                        "id_usuario"            => $id_usuario,
                                                                        "id_sucursal"           => $id_sucural,
                                                                        "fecha_seguimiento"     => date("Y-m-d H:i:s")
                                                                        )
                                                                );
            
            DAO::transBegin();
            try
            {
                SeguimientoDeServicioDAO::save($seguimiento_de_servicio);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el seguimiento de servicio ". $e);
                throw new Exception("No se pudo crear el seguimiento de servicio");
            }
            DAO::transEnd();
            Logger::log("Seguimiento creado exitosamente");
            return array( "id_seguimiento" => $seguimiento_de_servicio->getIdSeguimientoDeServicio() );
	}
  
	/**
 	 *
 	 *Dar por terminada una orden, cuando el cliente satisface el ultimo pago
 	 *
 	 * @param id_orden int Id de la orden a terminar
 	 **/
	public static function TerminarOrden
	(
		$tipo_venta, 
		$id_orden, 
		$saldo = null, 
		$descuento = null, 
		$tipo_de_pago = null, 
		$cheques = null, 
		$billetes_pago = null, 
		$billetes_cambio = null, 
		$id_venta_caja = null
	)
	{  
            Logger::log("Terminando orden de servicio ".$id_orden);
            
            //valida que la orden exista y que etse activa
            $validar = self::validarParametrosOrdenDeServicio($id_orden);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //valida el parametro descuento
            if(!is_null($descuento))
            {
                $validar = self::validarNumero($descuento, 1.8e200, "descuento");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
            }
            
            //valida el parametro saldo
            if(!is_null($saldo))
            {
                $validar = self::validarNumero($saldo, 1.8e200, "saldo");
                if(is_string($validar))
                {
                    Logger::error($validar);
                    throw new Exception($validar,901);
                }
            }
            
            //valida el parametro tipo de pago
            if(!is_null($tipo_de_pago))
            {
                if($tipo_de_pago!="cheque"&&$tipo_de_pago!="efectivo"&&$tipo_de_pago!="tarjeta")
                {
                    Logger::error("El tipo de pago (".$tipo_de_pago.") no es valido, tiene que ser cheque, efectivo o tarjeta");
                    throw new Exception("El tipo de pago (".$tipo_de_pago.") no es valido, tiene que ser cheque, efectivo o tarjeta",901);
                }
            }
            
            //Da por terminada la orden y cambia la fecha de entrega a la fecha actual.
            $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden);
            
            $orden_de_servicio->setActiva(0);
            $orden_de_servicio->setFechaEntrega(date("Y-m-d H:i:s"));
            
            //valida el parametro tipo_venta
            if($tipo_venta!="credito"&&$tipo_venta!="contado")
            {
                Logger::error("El parametro tipo de venta (".$tipo_venta.") tiene que ser 'credito' o 'contado'");
                throw new Exception("El parametro tipo de venta (".$tipo_venta.") tiene que ser 'credito' o 'contado'",901);
            }
            
            $servicio = ServicioDAO::getByPK($orden_de_servicio->getIdServicio());
            
            $usuario = UsuarioDAO::getByPK($orden_de_servicio->getIdUsuario());
            
            $productos_orden = ProductoOrdenDeServicioDAO::search( new ProductoOrdenDeServicio( array( "id_orden_de_servicio" => $id_orden ) ) );
            
            //El subtotal de la venta es el costo acumulado registrado en la orden de servicio
            $subtotal = $orden_de_servicio->getPrecio();
            
            
            //Se calcula el impuesto que se cargara a la venta
            
            $impuesto = 0;
            
            //Se toman los ids de los impuestos que afectan al servicio, a las clasificaciones del servicio,
            //a los productos, al cliente y a la clasificacion del cliente. 
            
            //Para eliminar a los repetidos, se guaradara como llave el id en el arreglo impuestos_actuales,
            //y antes de agregar el id del impuesto al arreglo id_impuestos, se verifica que esa llave no exista en impuestos_actuales
            
            $id_impuestos = array(); //arreglo que almacenara los ids de los impuestos
            $impuestos_actuales = array(); //arreglo que llevara el registro de que ids ya han sido guardados con su llave.
            
            //Impuestos por el servicio
            $impuestos_servicio = ImpuestoServicioDAO::search(new ImpuestoServicio( array( "id_servicio" => $servicio->getIdServicio() ) ));
            foreach($impuestos_servicio as $impuesto_servicio)
            {
                $key = $impuesto_servicio->getIdImpuesto();
                if(!array_key_exists($key, $impuestos_actuales))
                {
                    $impuestos_actuales[$key] = true;
                    array_push($id_impuestos,$key);
                }
            }
            
            //Impuestos por las clasificaciones del servicio
            $clasificaciones_servicio = ServicioClasificacionDAO::search( 
                    new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) ) 
                    );
            foreach($clasificaciones_servicio as $clasificacion_servicio)
            {
                $impuestos_clasificacion_servicio = ImpuestoClasificacionServicioDAO::search( 
                        new ImpuestoClasificacionServicio( 
                                array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) 
                            ) 
                        );
                foreach($impuestos_clasificacion_servicio as $impuesto_clasificacion_servicio)
                {
                    $key = $impuesto_clasificacion_servicio->getIdImpuesto();
                    if(!array_key_exists($key, $impuestos_actuales))
                    {
                        $impuestos_actuales[$key] = true;
                        array_push($id_impuestos,$key);
                    }
                }
            }
            
            //Impuestos por el cliente
            $impuestos_usuario = ImpuestoUsuarioDAO::search( new ImpuestoUsuario( 
                    array( "id_usuario" => $orden_de_servicio->getIdUsuarioVenta() ) 
                    ) );
            foreach($impuestos_usuario as $impuesto_usuario)
            {
                $key = $impuesto_usuario->getIdImpuesto();
                if(!array_key_exists($key, $impuestos_actuales))
                {
                    $impuestos_actuales[$key] = true;
                    array_push($id_impuestos,$key);
                }
            }
            
            //Impuestos por la clasificacion del usuario
            $impuestos_clasificacion_cliente = ImpuestoClasificacionClienteDAO::search( 
                    new ImpuestoClasificacionCliente( 
                            array( "id_clasificacion_cliente" => $usuario->getIdClasificacionCliente() ) 
                        ) 
                    );
            foreach($impuestos_clasificacion_cliente as $impuesto_clasificacion_cliente)
            {
                $key = $impuesto_clasificacion_cliente->getIdImpuesto();
                if(!array_key_exists($key, $impuestos_actuales))
                {
                    $impuestos_actuales[$key] = true;
                    array_push($id_impuestos,$key);
                }
            }
            
            //Impuestos por los productos
            
            //Los productos ya tienen la cantidad de impuesto que brindan al impuesto total, por lo que solo se suma su cantidad
            
            foreach($productos_orden as $producto_orden)
            {
                $impuesto+=$producto_orden->getImpuesto();
            }
            
            //Por cada impuesto recabado se realiza su operacion y se suma al impuesto final
            foreach($id_impuestos as $id_impuesto)
            {
                $imp = ImpuestoDAO::getByPK($id_impuesto);
                if(is_null($imp))
                {
                    Logger::error("FATAL! Se hizo referencia a un impuesto que no existe");
                    throw new Exception("ERROR FATAL! Se hizo referencia a un impuesto que no existe",901);
                }
                if($imp->getEsMonto())
                {
                    $impuesto+=($imp->getMontoPorcentaje()/100)*$subtotal;
                }
                else
                {
                    $impuesto+=$imp->getMontoPorcentaje();
                }
            }
            
            
            //Se calcula la retencion que se caragara a la venta
            
            $retencion = 0;
            
            //Se toman los ids de las retenciones que afectan al servicio, a las clasificaciones del servicio,
            //a los productos, al cliente y a la clasificacion del cliente. 
            
            //Para eliminar a los repetidos, se guaradara como llave el id en el arreglo retenciones_actuales,
            //y antes de agregar el id de la retencion al arreglo id_retenciones, se verifica que esa llave no exista en retenciones_actuales
            
            $id_retenciones = array(); //arreglo que almacenara los ids de las retenciones
            $retenciones_actuales = array(); //arreglo que llevara el registro de que ids ya han sido guardados con su llave.
            
            //Retenciones por el servicio
            $retenciones_servicio = RetencionServicioDAO::search(new RetencionServicio( array( "id_servicio" => $servicio->getIdServicio() ) ));
            foreach($retenciones_servicio as $retencion_servicio)
            {
                $key = $retencion_servicio->getIdRetencion();
                if(!array_key_exists($key, $retenciones_actuales))
                {
                    $retenciones_actuales[$key] = true;
                    array_push($id_retenciones,$key);
                }
            }
            
            //Retenciones por las clasificaciones del servicio
            $clasificaciones_servicio = ServicioClasificacionDAO::search( 
                    new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) ) 
                    );
            foreach($clasificaciones_servicio as $clasificacion_servicio)
            {
                $retenciones_clasificacion_servicio = RetencionClasificacionServicioDAO::search( 
                        new RetencionClasificacionServicio( 
                                array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) 
                            ) 
                        );
                foreach($retenciones_clasificacion_servicio as $retencion_clasificacion_servicio)
                {
                    $key = $retencion_clasificacion_servicio->getIdRetencion();
                    if(!array_key_exists($key, $retenciones_actuales))
                    {
                        $retenciones_actuales[$key] = true;
                        array_push($id_retenciones,$key);
                    }
                }
            }
            
            //Retenciones por el cliente
            $retenciones_usuario = RetencionUsuarioDAO::search( new RetencionUsuario( 
                    array( "id_usuario" => $orden_de_servicio->getIdUsuarioVenta() ) 
                    ) );
            foreach($retenciones_usuario as $retencion_usuario)
            {
                $key = $retencion_usuario->getIdRetencion();
                if(!array_key_exists($key, $retenciones_actuales))
                {
                    $retenciones_actuales[$key] = true;
                    array_push($id_retenciones,$key);
                }
            }
            
            //Retenciones por la clasificacion del usuario
            $retenciones_clasificacion_cliente = RetencionClasificacionClienteDAO::search( 
                    new RetencionClasificacionCliente( 
                            array( "id_clasificacion_cliente" => $usuario->getIdClasificacionCliente() ) 
                        ) 
                    );
            foreach($retenciones_clasificacion_cliente as $retencion_clasificacion_cliente)
            {
                $key = $retencion_clasificacion_cliente->getIdRetencion();
                if(!array_key_exists($key, $retenciones_actuales))
                {
                    $retenciones_actuales[$key] = true;
                    array_push($id_retenciones,$key);
                }
            }
            
            //Retenciones por los productos
            
            //Los productos ya tienen la cantidad de retencion que brindan a la retencion total, por lo que solo se suma su cantidad
            
            foreach($productos_orden as $producto_orden)
            {
                $retencion+=$producto_orden->getRetencion();
            }
            
            //Por cada retencion recabado se realiza su operacion y se suma a la retencion final
            foreach($id_retenciones as $id_retencion)
            {
                $ret = RetencionDAO::getByPK($id_retencion);
                if(is_null($ret))
                {
                    Logger::error("FATAL! Se hizo referencia a una retencion que no existe");
                    throw new Exception("ERROR FATAL! Se hizo referencia a una retencion que no existe",901);
                }
                if($ret->getEsMonto())
                {
                    $retencion+=($ret->getMontoPorcentaje()/100)*$subtotal;
                }
                else
                {
                    $retencion+=$ret->getMontoPorcentaje();
                }
            }
            
            
            //Se calcula el total de la venta
            
            $total = $subtotal + $impuesto + $retencion - ($descuento/100)*$subtotal;
            
            //Inicia el arreglo que se le pasara al metodo de venta para ordenes y para productos
            
            $detalle_orden = array(
                "descuento"             => $descuento,
                "id_orden_de_servicio"  => $id_orden
            );
            
            $detalle_productos = array();
            foreach($productos_orden as $producto_orden)
            {
                $temp = array(
                    "id_producto"   => $producto_orden->getIdProducto(),
                    "id_unidad"     => $producto_orden->getIdUnidad(),
                    "precio"        => $producto_orden->getPrecio(),
                    "cantidad"      => $producto_orden->getCantidad(),
                    "descuento"     => $producto_orden->getDescuento(),
                    "impuesto"      => $producto_orden->getImpuesto(),
                    "retencion"     => $producto_orden->getRetencion()
                );
                array_push($detalle_productos,$temp);
            }
            
            DAO::transBegin();
            try
            {
                OrdenDeServicioDAO::save($orden_de_servicio);
                SucursalesController::VenderCaja($retencion, $orden_de_servicio->getIdUsuarioVenta(),
                        $subtotal, $impuesto, $total, $descuento, $tipo_venta, $saldo, $cheques,
                        $tipo_de_pago,$billetes_pago, $billetes_cambio, $id_venta_caja, $detalle_productos, $detalle_orden);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo terminar la orden de servicio ".$e);
                if($e->getCode()==901)
                    throw new Exception ("No se pudo terminar la orden de servicio: ".$e->getMessage(), 901);
                throw new Exception("No se pudo terminar la orden de servicio, contacte al administrador del sistema");
            }
            DAO::transEnd();
            Logger::log("La orden de servicio se ha terminado exitosamente");
	}
  
	/**
 	 *
 	 *Da de baja un servicio que ofrece una empresa
 	 *
 	 * @param id_servicio int Id del servicio que ser� eliminado
 	 **/
	public static function Eliminar
	(
		$id_servicio
	)
	{  
            Logger::log("Desactivando servicio ".$id_servicio);
            
            //valida que el servicio exista y que no haya sido desactivado antes
            $validar = self::validarParametrosServicio($id_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            //Si el servicio forma parte de algun paquete activo no puede ser eliminado
            $servicios_paquete = OrdenDeServicioPaqueteDAO::search( new OrdenDeServicioPaquete( array( "id_servicio" => $id_servicio ) ) );
            foreach($servicios_paquete as $servicio_paquete)
            {
                $paquete = PaqueteDAO::getByPK($servicio_paquete->getIdServicio());
                if($paquete->getActivo())
                {
                    Logger::error("No se puede borrar este servicio pues el paquete ".$paquete->getIdPaquete()." aun lo contiene");
                    throw new Exception("No se puede borrar este servicio pues el paquete ".$paquete->getIdPaquete()." aun lo contiene");
                }
            }
            
            $servicio = ServicioDAO::getByPK($id_servicio);
            $servicio->setActivo(0);
            
            //Se obtienen los registros de las tablas servicio_empresa, servicio_clasificacion e impuesto_servicio
            //pues seran eliminados
            $servicios_empresa = ServicioEmpresaDAO::search( new ServicioEmpresa( array( "id_servicio" => $id_servicio ) ) );
            $servicios_clasificacion = ServicioClasificacionDAO::search( new ServicioClasificacion( array( "id_servicio" => $id_servicio ) ) );
            $impuestos_servicio = ImpuestoServicioDAO::search( new ImpuestoServicio(  array( "id_servicio" => $id_servicio  ) ) );
            
            DAO::transBegin();
            try
            {
                ServicioDAO::save($servicio);
                foreach($servicios_empresa as $servicio_empresa)
                {
                    ServicioEmpresaDAO::delete($servicio_empresa);
                }
                foreach($servicios_clasificacion as $servicio_clasificacion)
                {
                    ServicioClasificacionDAO::delete($servicio_clasificacion);
                }
                foreach($impuestos_servicio as $impuesto_servicio)
                {
                    ImpuestoServicioDAO::delete($impuesto_servicio);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido descativar el servicio ".$id_servicio." : ".$e);
                throw new Exception("No se ha podido descativar el servicio ".$id_servicio);
            }
            DAO::transEnd();
            LOgger::log("El servicio ha sido eliminado exitosamente");
	}
        
  
	
  
	/**
 	 *
 	 *En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio a la cual se le agregaran los productos
 	 * @param productos json Arreglo de objetos con ids de producto, de unidad, sus cantidades, su precio, su impuesto, retencion y descuento.
 	 **/
        public static function Agregar_productosOrden
	(
		$id_orden_de_servicio, 
		$productos
	)
        {
            Logger::log("Agregando productos a la orden de servicio ".$id_orden_de_servicio);
            
            //Se valida la orden de servicio
            $validar = self::validarParametrosOrdenDeServicio($id_orden_de_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //El precio de la orden de servicio se incrementara por cada precio encontrado en los productos
            $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden_de_servicio);
            
            //Se almacenaran todos los productos obtenidos con us informacion
            $producto_orden_de_servicio = new ProductoOrdenDeServicio( array( "id_orden_de_servicio" => $id_orden_de_servicio ) );
            
            DAO::transBegin();
            try
            {
                /*
                 * Por cada producto en el arreglo, se busca en la tabla de productos orden,
                 * pues puede ser que el usuario haya querido agregar mas producto al que ya habia.
                 * 
                 * Si no se encuentra en la tabla, simplemente se crea un nuevo registro
                 */
                foreach($productos as $producto)
                {
                    $producto_anterior = ProductoOrdenDeServicioDAO::getByPK(
                            $id_orden_de_servicio, $producto["id_producto"], $producto["id_unidad"]);
                    
                    if(!is_null($producto_anterior))
                    {
                        $producto_orden_de_servicio=$producto_anterior;
                        $cantidad_anterior = $producto_orden_de_servicio->getCantidad();
                        
                        $producto_orden_de_servicio->setCantidad($cantidad_anterior+$producto["cantidad"]);
                    }
                    else
                    {
                        $validar = self::validarParametrosProductoOrdenDeServicio(
                                $producto["id_producto"], $producto["precio"], $producto["cantidad"],
                                $producto["descuento"], $producto["impuesto"], $producto["retencion"], $producto["id_unidad"]);
                        if(is_string($validar))
                        {
                            throw new Exception($validar,901);
                        }

                        $producto_orden_de_servicio->setCantidad($producto["cantidad"]);
                        $producto_orden_de_servicio->setDescuento($producto["descuento"]);
                        $producto_orden_de_servicio->setIdProducto($producto["id_producto"]);
                        $producto_orden_de_servicio->setIdUnidad($producto["id_unidad"]);
                        $producto_orden_de_servicio->setImpuesto($producto["impuesto"]);
                        $producto_orden_de_servicio->setPrecio($producto["precio"]);
                        $producto_orden_de_servicio->setRetencion($producto["retencion"]);
                    }
                    //@TODO 
                    //La linea de codigo siguiente puede causar problemas, pues el precio de un producto puede cmabiar a lo largo del tiempo.
                    //Si este metodo fue llamado para agregar mas cantidad a uno ya existente para esta orden en un rango de tiempo
                    //donde el precio del producto cambio de la primera vez que fue agregado a esta, el precio registrado en la tabla
                    //sera el de la primera vez, pero el producto agregado recientemente ya tiene otro precio.
                    //
                    //Si este producto es retirado con el metodo Quitar_productoOrden se tiene que pasar el precio que tenia este 
                    //producto a la hora de agregarlo para que el precio total de la orden de servicio no se vea alterada.
                    //
                    $orden_de_servicio->setPrecio( $orden_de_servicio->getPrecio() + $producto["cantidad"]*$producto["precio"]);
                    
                    ProductoOrdenDeServicioDAO::save($producto_orden_de_servicio);
                    OrdenDeServicioDAO::save($orden_de_servicio);
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo agregar el producto a la orden: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo agregar el producto a la orden: ".$e->getMessage(),901);
                throw new Exception("No se pudo agregar el producto a la orden, consulte al administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Producto agregado exitosamente");
           
        }
        
        
  
	
  
	/**
 	 *
 	 *Este metodo se usa para quitar productos de una orden de servicio. Puede ser usado para reducir su cantidad o para retirarlo por completo
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio de la cual se moveran los productos
 	 * @param productos json Arreglo que contendra los ids de productos, de unidades y  sus cantidades a retirar
 	 **/
        public static function Quitar_productosOrden
	(
		$id_orden_de_servicio, 
		$productos
	)
        {
            Logger::log("Quitando productos a la orden de servicio ".$id_orden_de_servicio);
            
            //Se valida la orden de servicio
            $validar = self::validarParametrosOrdenDeServicio($id_orden_de_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //El precio de la orden de servicio se decrementara por cada producto encontrado
            $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden_de_servicio);
            
            DAO::transBegin();
            try
            {
                /*
                 * Por cada producto en el arreglo, se busca en la tabla de productos orden,
                 * si se editara su cantidad o se eliminara el registro depende de la cantidad
                 * recibida. Si es menor, solo se editara, si es igual o mayor se eliminara.
                 * 
                 * Si no se encuentra en la tabla, se regresa un error
                 */
                foreach($productos as $producto)
                {
                    $producto_orden_de_servicio = ProductoOrdenDeServicioDAO::getByPK(
                            $id_orden_de_servicio, $producto["id_producto"], $producto["id_unidad"]);
                    
                    if(!is_null($producto_orden_de_servicio))
                    {
                        $cantidad_anterior = $producto_orden_de_servicio->getCantidad();
                        
                        if($cantidad_anterior>$producto["cantidad"])
                        {
                            $producto_orden_de_servicio->setCantidad($cantidad_anterior - $producto["cantidad"]);
                            ProductoOrdenDeServicioDAO::save($producto_orden_de_servicio);
                        }
                        else
                        {
                            ProductoOrdenDeServicioDAO::delete($producto_orden_de_servicio);
                        }
                        
                        //@TODO
                        //La siguiente linea de codigo puede causar problemas, pues el precio de un producto puede cambiar con el tiempo.
                        //Si se agrego producto a uno que ya habia sido registrado anteriormente, el costo que manipulo al precio
                        //de la orden de servicio varío en cada una de las inserciones. El usuario debe tener en cuenta que
                        //el precio que pase a este metodo debe ser el correspondiente a esa cantidad, o si esta quitando todos 
                        //los productos tiene que tener mucho cuidado pues los precios de diferentes cantidades son distintos, y 
                        //el precio de la orden de servicio puede resultar incongruente.
                        //
                        
                        $orden_de_servicio->setPrecio($orden_de_servicio->getPrecio() - $producto["cantidad"]*$producto["precio"]);
                        
                        OrdenDeServicioDAO::save($orden_de_servicio);
                    }
                    else
                    {
                        throw new Exception("El producto recibido no ha sido encontrado",901);
                    }
                    
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo retirar el producto de la orden: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo retirar el producto de la orden: ".$e->getMessage(),901);
                throw new Exception("No se pudo retirar el producto de la orden, consulte al administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Producto agregado exitosamente");
        }
        
  }
