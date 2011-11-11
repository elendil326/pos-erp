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
                
                if($servicio->getActivo())
                    return "El servicio ".$id_servicio." no esta activo";
            }
            
            //valida que el usuario al que se le vende exista 
            if(!is_null($id_usuario_venta))
            {
                $usuario = UsuarioDAO::getByPK($id_usuario_venta);
                if(!is_null($usuario))
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
		$retenciones = "", 
		$impuestos = "", 
		$descuento = "", 
		$margen_utilidad = "", 
		$descripcion = "", 
		$garantia = "", 
		$nombre = ""
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
		$motivo_cancelacion = ""
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
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
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
		$costo_estandar, 
		$metodo_costeo, 
		$nombre_servicio, 
		$codigo_servicio, 
		$empresas, 
		$compra_en_mostrador, 
		$sucursales, 
		$descripcion_servicio = null, 
		$garantia = null, 
		$retenciones = null, 
		$impuestos = null, 
		$activo = true, 
		$clasificaciones = null, 
		$margen_de_utilidad = null, 
		$control_de_existencia = null, 
		$foto_servicio = null
	)
	{  
  
  
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
 	 * @param metodo_costeo string Mtodo de costeo del producto: 1 = Costo Promedio en Base a Entradas.2 = Costo Promedio en Base a Entradas Almacn.3 = ltimo costo.4 = UEPS.5 = PEPS.6 = Costo especfico.7 = Costo Estndar
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
		$sucursales = null, 
		$nombre_servicio = null, 
		$garantia = null, 
		$impuestos = null, 
		$metodo_costeo = null, 
		$empresas = null, 
		$codigo_servicio = null, 
		$descripcion_servicio = null, 
		$compra_en_mostrador = null, 
		$activo = null, 
		$control_de_existencia = null, 
		$foto_servicio = null, 
		$margen_de_utilidad = null, 
		$clasificaciones = null, 
		$retenciones = null, 
		$costo_estandar = null
	)
	{  
  
  
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
		$id_empresa = "", 
		$id_sucursal = "", 
		$fecha_desde = "", 
		$fecha_hasta = ""
	)
	{  
  
  
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
		$fecha_entrega, 
		$descripcion = null, 
		$adelanto = null
	)
	{  
  
  
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
  
  
	}
  
	/**
 	 *
 	 *Dar por terminada una orden, cuando el cliente satisface el ultimo pago
 	 *
 	 * @param id_orden int Id de la orden a terminar
 	 **/
	public static function TerminarOrden
	(
		$id_orden
	)
	{  
  
  
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
  
  
	}
  }
