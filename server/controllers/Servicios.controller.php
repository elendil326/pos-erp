<?php
require_once("interfaces/Servicios.interface.php");
/**
  *
  *
  *
  **/
	
class ServiciosController extends ValidacionesController implements IServicios{
  
        
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
                $e = self::validarLongitudDeCadena($nombre, 50, 100);
                if(is_string($e))
                    return $e;
                
                if(!is_null($id_clasificacion_servicio))
                {
                    $clasificaciones_servicio = array_diff(ClasificacionServicioDAO::search( 
                            new ClasificacionServicio( array("nombre" => trim($nombre)) ) ), 
                            array(ClasificacionServicioDAO::getByPK($id_clasificacion_servicio)));
                }
                else
                {
                    $clasificaciones_servicio = ClasificacionServicioDAO::search( new ClasificacionServicio( array("nombre" => trim($nombre)) ) );
                }
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
                $e = self::validarLongitudDeCadena($descripcion, 255, "descripcion");
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
                $e = self::validarLongitudDeCadena($descripcion, 255, "descripcion");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el motivo de cancelacion este en rango
            if(!is_null($motivo_cancelacion))
            {
                $e = self::validarLongitudDeCadena($motivo_cancelacion, 255, "motivo de cancelacion");
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

                if(is_null($servicio)){
	    			return "El servicio ".$id_servicio." no existe";
				}
                
                /*if(!$servicio->getActivo())
                    return "El servicio ".$id_servicio." esta inactivo";*/
            }
            
            //valida el rango del nombre de servicio y que no se repita
            if(!is_null($nombre_servicio))
            {
                $e = self::validarLongitudDeCadena($nombre_servicio, 150, 1024 );

				
                
                if(!is_null($id_servicio))
                {
                    $servicios = array_diff(ServicioDAO::search( 
                            new Servicio( array( "nombre_servicio" => trim($nombre_servicio) ) ) ), array(ServicioDAO::getByPK($id_servicio) ));
                }
                else
                {
                    $servicios = ServicioDAO::search( new Servicio( array( "nombre_servicio" => trim($nombre_servicio) ) ) );
                }
                foreach($servicios as $servicio)
                {
                    if($servicio->getActivo()){
						throw new BusinessLogicException("El nombre de servicio (".$nombre_servicio.") ya esta en uso por el servicio ".$servicio->getIdServicio());
						
					}

                }
            }
            
            //valida el metodo de costeo
            if(!is_null($metodo_costeo))
            {
                if( $metodo_costeo !="precio" && $metodo_costeo !="costo" && $metodo_costeo !="variable" )
                    return "El metodo de costeo (".$metodo_costeo.") es invalido, seleccione : precio, costo o variable";

                if( $metodo_costeo =="precio" && !is_numeric($precio) && $precio < 0 ){
                    return "Seleccione un valor de precio valido";                    
                }

                if( $metodo_costeo =="costo" && !is_numeric($costo_estandar) && $costo_estandar < 0 ){
                    return "Seleccione un valor de costo valido";                    
                }

            }
            
            //valida que el codigo de servicio este en rango y que no se repita
            if(!is_null($codigo_servicio))
            {
                $e = self::validarLongitudDeCadena($codigo_servicio, 20, "codigo de servicio");
                if(is_string($e))
                    return $e;
                
                if(!is_null($id_servicio))
                {
                    $servicios = array_diff(ServicioDAO::search( new Servicio( array( "codigo_servicio" => $codigo_servicio ) ) ), array(ServicioDAO::getByPK($id_servicio) ) );
                }
                else
                {
                    $servicios = ServicioDAO::search( new Servicio( array( "codigo_servicio" => $codigo_servicio ) ) );
                }

                foreach($servicios as $servicio)
                {

                	throw new BusinessLogicException("El codigo de servicio (".$codigo_servicio.") esta siendo usado por el servicio ".$servicio->getIdServicio ());
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
            
            //valida la descripcion del servicio
            if(!is_null($descripcion_servicio))
            {
                $e = self::validarLongitudDeCadena($descripcion_servicio, 255, "descripcion de servicio");
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
                $e = self::validarLongitudDeCadena($foto_servicio, 50, "foto del servicio");
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
                $id_empresa = null
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
            
            //No se encontro error
            return true;
        }
        
        /*
         * Valida los parametros de la tabla servicio_sucursal. Regresa un string con el error
         * si se encuentra alguno. De lo contrario regresa verdadero
         */
        private static function validarParametrosServicioSucursal
        (
                $id_sucursal = null
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
                $e = self::validarLongitudDeCadena($estado, 255, "estado");
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
		$descripcion = null,
		$garantia = null, 
		$impuestos = null, 
		$nombre = null, 
		$retenciones = null
	)
	{  
            Logger::log("Editando clasificacion de servicio ".$id_clasificacion_servicio);
            
            //se validan los parametros
            $validar = self::validarParametrosClasificacionServicio($id_clasificacion_servicio,$nombre,$garantia,$descripcion);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $clasificacion_servicio = ClasificacionServicioDAO::getByPK($id_clasificacion_servicio);
            if(!is_null($garantia))
            {
                $clasificacion_servicio->setGarantia($garantia);
            }
            if(!is_null($descripcion))
            {
                $clasificacion_servicio->setDescripcion($descripcion);
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
                    
                    $impuestos = object_to_array($impuestos);
                    
                    if(!is_array($impuestos))
                    {
                        throw new Exception("Los impuestos son invalidos",901);
                    }
                    
                    $impuesto_clasificacion_servicio = new ImpuestoClasificacionServicio(
                            array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto con id ".$impuesto." no existe",901);
                        
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
                    
                    $retenciones = object_to_array($retenciones);
                    
                    if(!is_array($retenciones))
                    {
                        throw new Exception("Las retenciones son invalidas",901);
                    }
                    
                    $retencion_clasificacion_servicio = new RetencionClasificacionServicio( 
                            array( "id_clasificacion_servicio" => $id_clasificacion_servicio ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion con id ".$retencion." no existe",901);
                        
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
                if($e->getCode()==901)
                    throw new Exception("No se pudo editar la clasificacion de servicio: ".$e->getMessage(),901);
                throw new Exception("No se pudo editar la clasificacion de servicio ",901);
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
		$activa = 1, 
		$descripcion = null, 
		$garantia = null, 
		$impuestos = null, 
		$retenciones = null
	)
	{  
            Logger::log("Creando nueva clasificacion de servicio");
            
            //se validan los parametros obtendios
            $validar = self::validarParametrosClasificacionServicio(null,$nombre,$garantia,$descripcion,$activa);
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
                    
                    $impuestos = object_to_array($impuestos);
                    
                    if(!is_array($impuestos))
                    {
                        throw new Exception("Los impuestos son invalidos",901);
                    }
                    
                    $impuesto_clasificacion_servicio = new ImpuestoClasificacionServicio(
                            array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto con id ".$impuesto." no existe",901);
                        
                        $impuesto_clasificacion_servicio->setIdImpuesto($impuesto);
                        ImpuestoClasificacionServicioDAO::save($impuesto_clasificacion_servicio);
                    }
                }/* Fin if de impuestos*/
                if(!is_null($retenciones))
                {
                    
                    $retenciones = object_to_array($retenciones);
                    
                    if(!is_array($retenciones))
                    {
                        throw new Exception("Las retenciones son invalidas",901);
                    }
                    
                    $retencion_clasificacion_servicio = new RetencionClasificacionServicio( 
                            array( "id_clasificacion_servicio" => $clasificacion_servicio->getIdClasificacionServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion con id ".$retencion." no existe",901);
                        
                        $retencion_clasificacion_servicio->setIdRetencion($retencion);
                        RetencionClasificacionServicioDAO::save($retencion_clasificacion_servicio);
                    }
                }/* Fin if de retenciones */
            }/* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva clasificacion de servicio: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear la nueva clasificacion de servicio: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear la nueva clasificacion de servicio",901);
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
	public static function Buscar
	(
		$activo = null, 
		$id_empresa = null, 
		$id_sucursal = null
	)
	{  
            Logger::log("Listando servicios...");
            

            
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
                $servicio_criterio_1 = ServicioDAO::search( new Servicio( array( "activo" => $activo ) )  );
            }
            else
            {
                $servicio_criterio_1 = ServicioDAO::getAll(null,null );
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

			return array( "resultados" => $servicios,
							"numero_de_resultados" => sizeof($servicios));

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
		$compra_en_mostrador, 
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_servicio, 
		$activo =  true , 
		$clasificaciones = null, 
		$control_de_existencia = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$extra_params = null, 
		$foto_servicio = null, 
		$garantia = null, 
		$impuestos = null, 
		$precio = null, 
		$retenciones = null, 
		$sucursales = null
	)
	{  
            Logger::log("Creando nuevo servicio `$nombre_servicio`...");


			$r = ServicioDAO::search(new Servicio(array("codigo_servicio" => $codigo_servicio)));
			
			if(sizeof($r) > 0){
				throw new BusinessLogicException("ya existe este codigo de servicio");
			}
		
            //se validan los parametros recibidos
            $validar = self::validarParametrosServicio(
								null,
								$nombre_servicio,
								$metodo_costeo,
                    			$codigo_servicio,
								$compra_en_mostrador,
								$activo,
								$descripcion_servicio,
                    			$costo_estandar,
								$garantia,
								$control_de_existencia,
								$foto_servicio,
								$precio);

            if(is_string($validar))
            {
                Logger::error($validar);
                throw new BusinessLogicException($validar);
            }
            
            //valida que se haya recibido el parametro esperado por el metodo de costeo
            if( ( $metodo_costeo == "precio" && is_null($precio) ) || ( $metodo_costeo == "costo" && is_null($costo_estandar) ) )
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
		                                "descripcion_de_servicio"   => $descripcion_servicio,
		                                "garantia"                  => $garantia,
		                                "control_existencia"        => $control_de_existencia,
		                                "foto_servicio"             => $foto_servicio,
		                                "precio"                    => $precio,
										"extra_params"				=> null
                                    ) 
								);

			if(!is_null($extra_params)){
				$servicio->setExtraParams(json_encode($extra_params));
			}

            //Se guarda el registro. Si se reciben empresas, sucursales, impuestos y/o retenciones, se guardan 
            //los respectivos registros con la informacion obtenida
            
            DAO::transBegin();
            try
            {
                ServicioDAO::save($servicio);
                if(!is_null($empresas))
                {
                    
                    $empresas = object_to_array($empresas);
                    
                    if(!is_array($empresas))
                    {
                        throw new Exception("Las empresas son invalidas",901);
                    }
                    
                    $servicio_empresa = new ServicioEmpresa( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($empresas as $empresa)
                    {
                        
                        $validar = self::validarParametrosServicioEmpresa($empresa);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        
                        $servicio_empresa->setIdEmpresa($empresa);
                        ServicioEmpresaDAO::save($servicio_empresa);
                    }
                }/* Fin if de empresas */
                if(!is_null($sucursales))
                {
                    
                    $sucursales = object_to_array($sucursales);
                    
                    if(!is_array($sucursales))
                    {
                        throw new Exception("Las sucursales no son validas",901);
                    }
                    
                    $servicio_sucursal = new ServicioSucursal( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($sucursales as $sucursal)
                    {
                        $validar = self::validarParametrosServicioSucursal($sucursal);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        
                        $servicio_sucursal->setIdSucursal($sucursal);
                        ServicioSucursalDAO::save($servicio_sucursal);
                    }
                }/* Fin if de sucursales */
                if(!is_null($clasificaciones))
                {
                    
                    $clasificaciones = object_to_array($clasificaciones);
                    
                    if(!is_array($clasificaciones))
                    {
                        throw new Exception("Las clasificaciones son invalidas",901);
                    }
                    
                    $servicio_clasificacion = new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($clasificaciones as $clasificacion)
                    {
                        if(is_null(ClasificacionServicioDAO::getByPK($clasificacion)))
                                throw new Exception("La clasificacion ".$clasificacion." no existe",901);
                        
                        $servicio_clasificacion->setIdClasificacionServicio($clasificacion);
                        ServicioClasificacionDAO::save($servicio_clasificacion);
                    }
                }/* Fin if de clasificaciones */
                if(!is_null($impuestos))
                {
                    
                    $impuestos = object_to_array($impuestos);
                    
                    if(!is_array($impuestos))
                    {
                        throw new Exception("Los impuestos son invalidos",901);
                    }
                    
                    $impuesto_servicio = new ImpuestoServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto ".$impuesto." no existe",901);
                        
                        $impuesto_servicio->setIdImpuesto($impuesto);
                        ImpuestoServicioDAO::save($impuesto_servicio);
                    }
                }/* Fin if de impuestos */
                if(!is_null($retenciones))
                {
                    
                    $retenciones = object_to_array($retenciones);
                    
                    if(!is_array($retenciones))
                    {
                        throw new Exception("Las retenciones son invalidas",901);
                    }
                    
                    $retencion_servicio = new RetencionServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe",901);
                        
                        $retencion_servicio->setIdRetencion($retencion);
                        RetencionServicioDAO::save($retencion_servicio);
                    }
                }/* Fin if de impuestos */
            }/* Fin try */
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear el nuevo servicio: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear el nuevo servicio: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear el nuevo servicio",901);
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
		$clasificaciones = null, 
		$codigo_servicio = null, 
		$compra_en_mostrador = null, 
		$control_de_existencia = null, 
		$costo_estandar = null, 
		$descripcion_servicio = null, 
		$empresas = null, 
		$extra_params = null, 
		$foto_servicio = null, 
		$garantia = null, 
		$impuestos = null, 
		$metodo_costeo = null, 
		$nombre_servicio = null, 
		$precio = null, 
		$retenciones = null, 
		$sucursales = null
	)
	{  
            Logger::log("Editando servicio ".$id_servicio);
            
            //valida los parametros recibidos
            $validar = self::validarParametrosServicio($id_servicio,$nombre_servicio,
                    $metodo_costeo,$codigo_servicio,$compra_en_mostrador,null,
                    $descripcion_servicio,$costo_estandar,$garantia,
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

            if(!is_null($extra_params))
            {
                $servicio->setExtraParams(json_encode($extra_params));
            }

            if(!is_null($foto_servicio))
            {
                $servicio->setFotoServicio($foto_servicio);
            }
            if(!is_null($costo_estandar))
            {
    
                if(!is_numeric($costo_estandar) || $costo_estandar < 0){
                    throw new Exception("Indique un valor de costo valido");
                }

                $servicio->setCostoEstandar($costo_estandar);
            }
            if(!is_null($precio))
            {

                if(!is_numeric($precio) ||$precio < 0){
                    throw new Exception("Indique un valor de precio valido");
                }

                $servicio->setPrecio($precio);
            }
            if(!is_null($metodo_costeo))
            {

                if($metodo_costeo == "costo" && is_null($costo_estandar) ){
                    throw new Exception("Indique un valor de costo");
                }

                if($metodo_costeo == "precio" && is_null($precio) ){
                    throw new Exception("Indique un valor de precio");
                }

                $servicio->setMetodoCosteo($metodo_costeo);
            }
            
            //Se verifica que se cuente con el atributo que busca el metodo de costeo
            if( ( $servicio->getMetodoCosteo() == "precio" && is_null($servicio->getPrecio()) ) || $servicio->getMetodoCosteo() == "costo" && is_null($servicio->getCostoEstandar()) )
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
                    
                    $empresas = object_to_array($empresas);
                    
                    if(!is_array($empresas))
                    {
                        throw new Exception("Las empresas son invalidas",901);
                    }
                    
                    $servicio_empresa = new ServicioEmpresa( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($empresas as $empresa)
                    {
                        $validar = self::validarParametrosServicioEmpresa($empresa);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        
                        $servicio_empresa->setIdEmpresa($empresa);
                        ServicioEmpresaDAO::save($servicio_empresa);
                    }
                    $servicios_empresa = ServicioEmpresaDAO::search( new ServicioEmpresa( array( "id_servicio" => $id_servicio ) ) );
                    foreach($servicios_empresa as $s_e)
                    {
                        $encontrado = false;
                        foreach($empresas as $empresa)
                        {
                            if($empresa == $s_e->getIdEmpresa())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ServicioEmpresaDAO::delete ($s_e);
                    }
                }/* Fin if de empresas */
                if(!is_null($sucursales))
                {
                    
                    $sucursales = object_to_array($sucursales);
                    
                    if(!is_array($sucursales))
                    {
                        throw new Exception("Las sucursales no son validas",901);
                    }
                    
                    $servicio_sucursal = new ServicioSucursal( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($sucursales as $sucursal)
                    {
                        $validar = self::validarParametrosServicioSucursal($sucursal);
                        if(is_string($validar))
                            throw new Exception($validar,901);
                        
                        $servicio_sucursal->setIdSucursal($sucursal);
                        ServicioSucursalDAO::save($servicio_sucursal);
                    }
                    $servicios_sucursal = ServicioSucursalDAO::search( new ServicioSucursal( array( "id_servicio" => $id_servicio ) ) );
                    foreach($servicios_sucursal as $s_s)
                    {
                        $encontrado = false;
                        foreach($sucursales as $sucursal)
                        {
                            if($sucursal == $s_s->getIdSucursal())
                                $encontrado=true;
                        }
                        if(!$encontrado)
                            ServicioSucursalDAO::delete ($s_s);
                    }
                }/* Fin if de sucursales */
                if(!is_null($clasificaciones))
                {
                    
                     $clasificaciones = object_to_array($clasificaciones);
                    
                    if(!is_array($clasificaciones))
                    {
                        throw new Exception("Las clasificaciones son invalidas",901);
                    }
                    
                    $servicio_clasificacion = new ServicioClasificacion( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($clasificaciones as $clasificacion)
                    {
                        if(is_null(ClasificacionServicioDAO::getByPK($clasificacion)))
                                throw new Exception("La clasificacion ".$clasificacion." no existe",901);
                        
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
                    
                    $impuestos = object_to_array($impuestos);
                    
                    if(!is_array($impuestos))
                    {
                        throw new Exception("Los impuestos son invalidos",901);
                    }
                    
                    $impuesto_servicio = new ImpuestoServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($impuestos as $impuesto)
                    {
                        if(is_null(ImpuestoDAO::getByPK($impuesto)))
                                throw new Exception("El impuesto ".$impuesto." no existe",901);
                        
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
                    
                    $retenciones = object_to_array($retenciones);
                    
                    if(!is_array($retenciones))
                    {
                        throw new Exception("Las retenciones son invalidas",901);
                    }
                    
                    $retencion_servicio = new RetencionServicio( array( "id_servicio" => $servicio->getIdServicio() ) );
                    foreach($retenciones as $retencion)
                    {
                        if(is_null(RetencionDAO::getByPK($retencion)))
                                throw new Exception("La retencion ".$retencion." no existe",901);
                        
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
                if($e->getCode()==901)
                    throw new Exception("No se pudo editar el servicio: ".$e->getMessage(),901);
                throw new Exception("No se pudo editar el servicio",901);
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
		$activa = null, 
		$cancelada = null, 
		$fecha_desde = null, 
		$fecha_hasta = null, 
		$id_servicio = null, 
		$id_usuario_venta = null
	)
	{  
            Logger::log("listando ordenes....");


            $os = new OrdenDeServicio(array(
                        "id_servicio"       => $id_servicio,
                        "id_usuario_venta"  => $id_usuario_venta,
                        "activa"            => $activa,
                        "cancelada"         => $cancelada
                    ));
            
            $ordenes = OrdenDeServicioDAO::search($os);

            return array("numero_de_resultados" => count($ordenes), "resultados" => $ordenes);
            
            /*
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
                        $orden_criterio_2->setFechaOrden (time());
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
			*/
			$ordenes = OrdenDeServicioDAO::getAll();
			
            Logger::log("Lista de ordenes traida exitosamente con ".count($ordenes)." elementos");

            return array("numero_de_resultados" => count($ordenes), "resultados" => $ordenes);
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

            //Se valida que exista la orden de servicio
            $orden = OrdenDeServicioDAO::getByPK($id_orden);
            if(is_null($orden))
            {
                Logger::error("La orden de servicio ".$id_orden." no existe");
                throw new Exception("La orden de servicio ".$id_orden." no existe");
            }

            $detalle_orden = $orden->asArray();

            $detalle_orden["segumientos"] =	SeguimientoDeServicioDAO::search( 
						new SeguimientoDeServicio( array( "id_orden_de_servicio" => $id_orden ) )
					);

			$detalle_orden["gastos"] = GastoDAO::search(
						new Gasto( array( "id_orden_de_servicio" => $id_orden ) ) 
					);

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
		$id_cliente, 
		$id_servicio, 
		$adelanto = null, 
		$cliente_reporta = null, 
		$condiciones_de_recepcion = null, 
		$descripcion = "", 
		$extra_params = null, 
		$fecha_entrega = "", 
		$fotografia = null, 
		$id_usuario_asignado = null, 
		$precio = null
	)
	{  
            Logger::log("Creando nueva orden de servicio...");
            Logger::log("   id_servicio=" . $id_servicio);
            Logger::log("   id_cliente =" . $id_cliente);			

            //Se obtiene al usuario de la sesion actual
            $s = SesionController::Actual();

            if(is_null($s)){
                Logger::error("No se ha podido obtener al usuario de la sesion. Ya inicio sesion?");
                throw new AccessDeniedException("No se ha podido obtener al usuario de la sesion.");
            }

			$id_usuario = $s["id_usuario"];
			$cliente = UsuarioDAO::getByPK($id_cliente);
            $saldo_cliente = $cliente->getSaldoDelEjercicio();//se trae el monto que le resta por disponer de su limite de credito

/*			if( $saldo_cliente < $precio )
				throw new InvalidDataException("El saldo del cliente es insuficiente ($ {$saldo_cliente})");
*/
			
            //Valida que los datos sean correctos
            $validar = self::validarParametrosOrdenDeServicio(null, $id_servicio, $id_cliente, $descripcion, null, $adelanto);


            //Si no se recibe adelanto se toma como cero
            if(is_null($adelanto)){
	    		$adelanto = 0;
	
			}else{

                if($adelanto < 0){
                    throw new InvalidDataException("No es un valor de adelanto valido");
                }
        
            }

			if($adelanto > $precio){
				throw new InvalidDataException("El monto del adelanto rebaza el monto del servicio");
			}
			
			$servicio = ServicioDAO::getByPK($id_servicio);
			
			if(is_null($servicio)){
				throw new InvalidDataException("Este servicio no existe");
			}
			
			

			
			
			$subtotal = 0;
			
			if($servicio->getMetodoCosteo() == "variable"){
				if(is_null($precio)){
					throw new InvalidDataException("Este servicio es de precio variable y no se envio el precio");
				}

                if($precio < 0){
                    throw new InvalidDataException("$precio no es un precio valido, es menor a 0");
                }

				$subtotal = $precio;
			}else{
				$subtotal = $servicio->getPrecio();	
				
				if(is_null($subtotal)){
					Logger::error("el precio de este servicio esta mal!");
					$subtotal = 0;
				}			
			}



/*
			//Figu: al llegar a este punto si tiene el saldo de la venta, pero se valida aun asi que su limite de credito cubra ese subtotal
			//esto por si en algun momento se actualiza el limite de credito del cliente (que el admin del sis le decremente su limite por cualquier razon)
			if(UsuarioDAO::getByPK($id_cliente)->getLimiteCredito() < $subtotal){
				throw new BusinessLogicException("El limite de credito no cubre este monto");
			}
*/			

            //Se inicializa el registro de orden de servicio
            $orden_de_servicio = new OrdenDeServicio( array( 
                                                            
                                                            "id_servicio"       => $id_servicio,
                                                            "id_usuario_venta"  => $id_cliente,
                                                            "id_usuario"        => $id_usuario,
                                                            "fecha_orden"       => time(),
                                                            "fecha_entrega"     => $fecha_entrega,
                                                            "activa"            => 1,
                                                            "cancelada"         => 0,
                                                            "descripcion"       => $descripcion,
                                                            "adelanto"          => $adelanto,
                                                            "precio"            => $subtotal
                                                            
                                                            )
                                                    );
			//ok, ya tengo el servicio, vamos a ver si necesito parametros extra
			if(!is_null( $servicio->getExtraParams() )){
				Logger::log("El servicio require parametros extra.");
				//si se necesitan, vamos a ver cuales son,
				$extra_params_sent		= $extra_params;
				$extra_params_required	= json_decode( $servicio->getExtraParams() );
				
				//no se enviaron los parametros extra?
				if(is_null($extra_params_sent)){
					Logger::warn("no se enviaron parametros extra");
				}
				
				foreach ($extra_params_required	as $epr) {
					Logger::log("Extraparam:" . $epr->desc);
				}
				
				$orden_de_servicio->setExtraParams( json_encode($extra_params) );
			}
			
            DAO::transBegin();
            try{
				Logger::log("Insertando la orden de servicio....");
                $orden = OrdenDeServicioDAO::save($orden_de_servicio);
    
        	}catch(Exception $e){
                DAO::transRollback();
                Logger::error($e->getMessage());
                throw new InvalidDatabaseOperationException("No se pudo crear la nueva orden de servicio");

            }

			$s = SesionController::Actual();

			//proceder a insertar venta a credito para este servicio
			/*
			
			
			*/
            DAO::transEnd();

            Logger::log("Orden de servicio creada exitosamente:");
	    	Logger::log("	orden de servicio=" . $orden_de_servicio->getIdOrdenDeServicio());


			//ok, ya se hizo correctamente, vamos a ver si le enviamos correo al cliente

			if(!is_null($cliente->getCorreoElectronico())){

				Logger::log("enviando correo a " .  $cliente->getCorreoElectronico() );

				$servicio = ServicioDAO::getByPK($id_servicio);

				$cuerpo = "Estimado " . $cliente->getNombre() . "\n\n"
						. "Le escribimos para informale que su orden de servcio "
						. "numero ". $orden_de_servicio->getIdOrdenDeServicio() . " "
						. "referente a " . $servicio->getNombreServicio(). " "
						. "esta siendo procesada y usted puede revisar su estatus en "
						. "cualquier momento mediante nuestra pagina web en: \n\n"
						. "http://pos2.labs2.caffeina.mx/front_ends/" . INSTANCE_TOKEN . "/?from=email" ;


				$destinatario = $cliente->getCorreoElectronico();

				$titulo = "Su orden de servicio ";

				POSController::EnviarMail(		$cuerpo, $destinatario, $titulo );
			}

            return array( "id_orden" => (int)$orden_de_servicio->getIdOrdenDeServicio() );
            
	}
  

	/**
 	 *
 	 *Edita una orden de servicio, generalmente para cambiar de usuario asignado o cambiar el precio.
 	 *
 	 * @param id_orden int la orden que se intenta editar
 	 * @param extra_params json Si esta orden requiere parametros, se enviaran en forma de json. Se evaluaran segun las reglas de extra_params, algunos pueden ser obligatorios y asi.
 	 * @param fecha_entrega int Fecha en que se entregara el servicio. En caso de aplicar. Unix Time Stamp
 	 * @param id_usuario_asignado int id del usuario que tiene asginada esta orden.... puede ser nadie.
 	 * @param precio float en caso de que el metodo de costeo sea variable, hay que mandar un precio final
 	 * @return id_orden int la orden que se ha editado
 	 **/
  public static function EditarOrden
	(
		$id_orden, 
		$extra_params = null, 
		$fecha_entrega = null, 
		$id_usuario_asignado = null, 
		$precio = null
	){
		Logger::log("Editando orden $id_orden");
		
		$orden = OrdenDeServicioDAO::getByPK( $id_orden );
		
		if(is_null($orden)){
			Logger::warn("Esta orden de servicio no existe... saliendo.");
			throw new InvalidDataException("Esta orden de servicio no existe");
		}
				
		if(!is_null($extra_params)){
			//aqui falta validar esos extra_params
			$orden->setExtraParams( $extra_params );
		}
		
		if(!is_null($fecha_entrega)){
			$orden->setFechaEntrega($fecha_entrega);
		}
		
		
		if(!is_null($precio)){
			$orden->setPrecio($precio);
		}
		
		
		if(!is_null($id_usuario_asignado)){
			$orden->setIdUsuarioAsignado( $id_usuario_asignado );
		}
		
		/*$saldo_cliente = UsuarioDAO::getByPK($orden->getIdUsuarioVenta())->getSaldoDelEjercicio();//se trae el monto que le resta por disponer de su limite de credito

		if( $saldo_cliente < $precio )
			throw new InvalidDataException("El saldo del cliente es insuficiente ($ {$saldo_cliente})");
			*/
		
		try{
			OrdenDeServicioDAO::save( $orden );
			
		}catch(Exception $e){
			Logger::error($e);
			throw InvalidDatabaseException($e);
		}
		
		

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
		$id_orden_de_servicio, 
		$abono = null, 
		$id_localizacion = null, 
		$nota = null
	)
	{  
            Logger::log("Creando nuevo seguimiento de orden...");
            
			if(is_null($id_orden_de_servicio)){
				throw new InvalidDataException("id_orden_de_servicio is a required field");
			}

            //Se obtiene al usuario de la sesion
			$sesion = SesionController::Actual();
			
            $id_usuario = $sesion["id_usuario"];
			$id_sucursal = $sesion["id_sucursal"];

            //Se validan los parametros recibidos
            /*$validar = self::validarParametrosSeguimiento(null, $id_orden_de_servicio, $id_localizacion, $nota);

            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }*/
            
            $seguimiento_de_servicio = new SeguimientoDeServicio( array(  
													"id_localizacion"       => $id_localizacion,
													"id_orden_de_servicio"  => $id_orden_de_servicio,
													"estado"                => $nota,
													"id_usuario"            => $id_usuario,
													"id_sucursal"           => $id_sucursal,
													"fecha_seguimiento"     => time()
                                                   ));
            
            DAO::transBegin();
            try{
                SeguimientoDeServicioDAO::save($seguimiento_de_servicio);

            }catch(Exception $e){
	
                DAO::transRollback();

                Logger::error($e->getMessage());

                throw new InvalidDatabaseOperationException("No se pudo crear el seguimiento de servicio");
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
		$id_orden, 
		$id_venta = null
	)
	{  
		
			Logger::log("Terminando orden " . $id_orden . " ");
			
			if(!is_null($id_venta)){

				Logger::log("Asignando orden a id_venta=" . $id_venta);
				
				//que la venta exista
				$v = VentaDAO::getByPK($id_venta);
				
				if(is_null($v)){
					throw new InvalidDataException("La venta a la que se quiere asignar no existe.");
				}
			}


			
            //ver que exista la orden de servicio
			$ods = OrdenDeServicioDAO::getByPK( $id_orden);
			
			if(is_null($ods)){
				throw new InvalidDataException("La orden de servicio que desea terminar no existe");
			}


			if(!$ods->getActiva()){
				throw new BusinessLogicException("La orden que quieres terminar ya no esta terminada.");
			}


			DAO::transBegin();
			
			try{
				$ods->setActiva(0);
				OrdenDeServicioDAO::save($ods);
				
			}catch(Exception $e){
				throw InvalidDatabaseOperationException($e);
				
			}


			if(!is_null($id_venta)){
				$vo = new VentaOrden();
				$vo->setIdVenta( $id_venta );
				$vo->setIdOrdenDeServicio( $id_orden );
				$vo->setPrecio( $ods->getPrecio() );
				$vo->setDescuento( 0);
				$vo->setImpuesto( 0 );
				$vo->setRetencion( 0);
				
				
                //Actualizar totales

                $ventaVo = VentaDAO::getByPK($id_venta);
                


				try{
					VentaOrdenDAO::save( $vo );
					
				}catch(Exception $e){
					throw InvalidDatabaseOperationException($e);
					
				}
				
				
				
			}else{
				
				//crearle una nueva venta
				$venta = new Venta();

				$s = SesionController::Actual();

				Logger::error( "There is plenty of hard-coded stuff here !");

				$venta->setIdCompradorVenta	( $ods->getIdUsuarioVenta() );
				$venta->setTipoDeVenta		( "contado" );
				$venta->setFecha			(time());
				$venta->setSubtotal			(0);
				$venta->setEsCotizacion		(0);			
				$venta->setImpuesto			(0);
				$venta->setTotal			(0);
				$venta->setIdSucursal		($s["id_sucursal"]);
				$venta->setIdUsuario		($s["id_usuario"]);
				$venta->setSaldo			(0);//si hay adelanto se resta al saldo de la venta el adelanto, esta resta se hace al insertar el abono_venta
				$venta->setCancelada		(false);
				$venta->setRetencion		(0);

				//vamos a ver si este dude tiene suficient credito para esto



				try{
					Logger::log("Insertando la venta ....");
					
	                VentaDAO::save( $venta );

	        	}catch(Exception $e){
	                DAO::transRollback();
	                Logger::error($e->getMessage());
	                throw new InvalidDatabaseOperationException("No se pudo crear la nueva orden de servicio");

	            }


				$venta_orden = new VentaOrden();

				$venta_orden->setIdVenta			( $venta->getIdVenta() );
				$venta_orden->setIdOrdenDeServicio	( $ods->getIdOrdenDeServicio() );
				$venta_orden->setPrecio				( 0);
				$venta_orden->setDescuento			( 0);
				$venta_orden->setImpuesto			( 0);
				$venta_orden->setRetencion			( 0);


				try{
					Logger::log("Insertando la orden de venta....");
	                VentaOrdenDAO::save( $venta_orden );

	        	}catch(Exception $e){
	                DAO::transRollback();
	                Logger::error($e->getMessage());
	                throw new InvalidDatabaseOperationException("No se pudo crear la nueva orden de servicio");
	            }
	
				$id_venta = $venta->getIdVenta();
				
			}


			DAO::transEnd();
	
		
            Logger::log("La orden de servicio se ha terminado exitosamente");
			

			return array(
				"id_venta" => $id_venta
			);

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
            Logger::log("Desactivando servicio ". $id_servicio . " ...");
            
            //valida que el servicio exista y que no haya sido desactivado antes
            $validar = self::validarParametrosServicio($id_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new InvalidDataException($validar);
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
            
			//revisar que no tenga ordenes de servicio activas 
			$res = OrdenDeServicioDAO::search( new OrdenDeServicio( array( "id_servicio" => $id_servicio,
			 														"activa"	=> 1) ) );
			
			if(sizeof($res) > 0){
				Logger::log("Intento borrar un servicio que tiene ordenes de servicio activas... ");
				throw new BusinessLogicException("Imposible eliminar un servicio que tiene ordenes abiertas");
			}

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
 	 *Este metodo se usa para quitar productos de una orden de servicio. Puede ser usado para reducir su cantidad o para retirarlo por completo
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio de la cual se moveran los productos
 	 * @param productos json Arreglo que contendra los ids de productos, de unidades y  sus cantidades a retirar
 	 **/
    static function ProductosQuitarOrden
    (
        $id_orden_de_servicio, 
        $productos
    ){
		 Logger::log("Quitando productos a la orden de servicio ".$id_orden_de_servicio);
            
            //Se valida la orden de servicio
            $validar = self::validarParametrosOrdenDeServicio($id_orden_de_servicio);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
             //valida que los productos sean validos
            $productos = object_to_array($productos);
            if(!is_array($productos))
            {
                throw new Exception("Los productos son invalidos",901);
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
                    
                    if
                    (
                            !array_key_exists("id_producto", $producto)     ||
                            !array_key_exists("id_unidad", $producto)       ||
                            !array_key_exists("cantidad", $producto)        ||
                            !array_key_exists("precio", $producto)          
                    )
                    {
                        throw new Exception("Los productos no contienen los parametros necesarios",901);
                    }
                    
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



	/**
 	 *
 	 *En algunos servicios, se realiza la venta de productos de manera indirecta, por lo que se tiene que agregar a la orden de servicio.
 	 *
 	 * @param id_orden_de_servicio int Id de la orden de servicio a la cual se le agregaran los productos
 	 * @param productos json Arreglo de objetos con ids de producto, de unidad, sus cantidades, su precio, su impuesto, retencion y descuento.
 	 **/
	static function ProductosAgregarOrden
    (
        $id_orden_de_servicio, 
        $productos
    ){
		Logger::log("Agregando productos a la orden de servicio ".$id_orden_de_servicio);
        
        //Se valida la orden de servicio
        $validar = self::validarParametrosOrdenDeServicio($id_orden_de_servicio);
        if(is_string($validar))
        {
            Logger::error($validar);
            throw new Exception($validar,901);
        }
        
        //valida que los productos sean validos
        $productos = object_to_array($productos);
        if(!is_array($productos))
        {
            throw new Exception("Los productos son invalidos",901);
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
                
                if
                (
                        !array_key_exists("id_producto", $producto)     ||
                        !array_key_exists("id_unidad", $producto)       ||
                        !array_key_exists("cantidad", $producto)        ||
                        !array_key_exists("precio", $producto)          ||
                        !array_key_exists("descuento", $producto)       ||
                        !array_key_exists("impuesto", $producto)        ||
                        !array_key_exists("retencion", $producto)
                )
                {
                    throw new Exception("Los productos no contienen los parametros necesarios",901);
                }
                
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

  }
