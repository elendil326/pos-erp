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
                                                            "adelanto"          => $adelanto
                                                            
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
		$id_orden, 
		$tipo_venta, 
		$descuento = null, 
		$saldo = null, 
		$tipo_de_pago = null
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
            
            $orden_de_servicio = OrdenDeServicioDAO::getByPK($id_orden);
            
            $orden_de_servicio->setActiva(0);
            $orden_de_servicio->setFechaEntrega(date("Y-m-d H:i:s"));
            
            DAO::transBegin();
            try
            {
                OrdenDeServicioDAO::save($orden_de_servicio);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo terminar la orden de servicio ".$e);
                throw new Exception("No se pudo terminar la orden de servicio");
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
  }
