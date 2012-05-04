<?php
require_once("interfaces/Tarifas.interface.php");
/**
  *
  *
  *
  **/
	
class TarifasController extends ValidacionesController implements ITarifas{

      
	/**
	 * 
	 * 
	 * 
	 * */
  	public static function _CalcularTarifa( VO $obj, $tipo ){
		
		if( !( ($obj instanceof Producto)
			|| ($obj instanceof Servicio)
			|| ($obj instanceof Paquete) )
		 ){
			throw new Excpetion( "Debes enviar una instancia de Producto, Servicio o Paquete al calcular la tarifa" );
		}
		
		$tarifas = TarifaDAO::obtenerTarifasActuales($tipo);

		$respuesta = array();

		foreach($tarifas as $t)	{

			array_push( $respuesta, array(
				"id_tarifa"   => $t["id_tarifa"],
				"precio"	  => ReglaDAO::aplicarReglas( $t["reglas"], $obj ),
				"descripcion" => $t["nombre"]
			));
		}

		return $respuesta;
	}

    public static function Calcular(
        $tipo_tarifa, 
        $cantidad = null, 
        $id_paquete = null, 
        $id_producto = null, 
        $id_servicio = null, 
        $id_tarifa = null, 
        $id_unidad = null
    ){
        
    }


        
        /*
         * Valida los parametros de un producto
         */
//        private static function validarProducto
//        (
//                $id_producto = null,
//                $id_unidad = null,
//                $tipo_tarifa = null
//        )
//        {
//            if(!is_null($id_producto))
//            {
//                //Se valida que el producto exista, que este activo y si se busca un precio de compra, que el producto se pueda comprar.
//                $producto = ProductoDAO::getByPK($id_producto);
//                if(is_null($producto))
//                {
//                    return "El producto ".$id_producto." no existe";
//                }
//
//                if(!$producto->getActivo())
//                {
//                    return "El producto ".$id_producto."  no esta activo";
//                }
//
//                if(!$producto->getCompraEnMostrador()&&$tipo_tarifa=="compra")
//                {
//                    return "Se quiere averiguar el precio de compra de un producto que no se puede comprar en mostrador";
//                }
//            }
//
//            //valida la unidad si es que se recibio
//            if(!is_null($id_unidad))
//            {
//                $unidad = UnidadDAO::getByPK($id_unidad);
//                if(is_null($unidad))
//                {
//                    Logger::error("La unidad con id ".$id_unidad." no existe");
//                    throw new Exception("La unidad con id ".$id_unidad." no existe",901);
//                }
//
//                if(!$unidad->getActiva())
//                {
//                    Logger::error("La unidad ".$id_unidad." no esta activa");
//                    throw new Exception("La unidad ".$id_unidad." no esta activa",901);
//                }
//            }
//        }
//        
//        /*
//         * Valida los parametros de un servicio
//         */
//        private static function validarServicio
//        (
//                $id_servicio 
//        )
//        {
//            $servicio = ServicioDAO::getByPK($id_servicio);
//            if(is_null($servicio))
//            {
//                return "El servicio ".$id_servicio."  no existe";
//            }
//            
//            if(!$servicio->getActivo())
//            {
//                return "El servicio ".$id_servicio." no esta activo";
//            }
//        }
//        
//        /*
//         * Valida los parametros de un paquete
//         */
//        private static function validarPaquete
//        (
//                $id_paquete
//        )
//        {
//            $paquete = PaqueteDAO::getByPK($id_paquete);
//            if(is_null($paquete))
//            {
//                return "El paquete ".$id_paquete." no existe";
//            }
//            
//            if(!$paquete->getActivo())
//            {
//                return "El paquete ".$id_paquete." no esta activo";
//            }
//        }
        
        /*
         * Valida los parametros de la tabla tarifa
         */
        private static function ValidarParametrosTarifa
        (
                $id_tarifa = null,
                $nombre = null,
                $tipo_tarifa = null,
                $id_moneda = null
        )
        {
            //Valida que la tarifa exista y este activa
            if(!is_null($id_tarifa))
            {
                $tarifa = TarifaDAO::getByPK($id_tarifa);
                if(is_null($tarifa))
                {
                    return  "La tarifa ".$id_tarifa." no existe";
                }
                
                if(!$tarifa->getActiva())
                {
                    return "La tarifa ".$id_tarifa." esta inactiva";
                }
            }
            
            //valida que el nombre sea valido y que no se repita
            if(!is_null($nombre))
            {

				if(!self::validarLongitudDeCadena($nombre, 0, 95)){
					throw InvalidDataException("nombre muy largo");
				}

                
                if(!is_null($id_tarifa))
                {
                    $tarifas = array_diff(TarifaDAO::search( new Tarifa( array( "nombre" => trim($nombre) ) ) ), array(TarifaDAO::getByPK($id_tarifa)));
                }
                else
                {
                    $tarifas = TarifaDAO::search( new Tarifa( array( "nombre" => trim($nombre) ) ) );
                }
                foreach($tarifas as $tarifa)
                {
                    if($tarifa->getActiva())
                    {
                        return "El nombre (".trim($nombre).") ya esta en uso por la tarifa ".$tarifa->getIdTarifa();
                    }
                }
                
            }
            
            //valida que el tipo de tarifa sea valido
            if
            (
                    !is_null($tipo_tarifa)      &&
                    $tipo_tarifa != "compra"    &&
                    $tipo_tarifa != "venta"
            )
            {
                return "El tipo de tarifa ".$tipo_tarifa." no es valido, tiene que ser 'compra' o 'venta'";
            }
            
            //valida la moneda recibida
            if(!is_null($id_moneda))
            {
                $moneda = MonedaDAO::getByPK($id_moneda);
                if(is_null($moneda))
                {
                    return "La moneda ".$id_moneda." no existe";
                }
                
                if(!$moneda->getActiva())
                {
                    return "La moneda ".$id_moneda." esta inactiva";
                }
            }
            
            //no se encontro error, regresa verdadero
            return true;
        }
       
       
        
        /*
         * Valida los parametros de la tabla Version
         */
        private static function ValidarParametrosVersion
        (
                $id_version = null,
                $nombre = null
        )
        {
            //valida que la version exista
            if(!is_null($id_version))
            {
                if(is_null(VersionDAO::getByPK($id_version)))
                {
                    return "La version ".$id_version." no existe";
                }
            }
            
            //valida que el nombre este en rango
            if(!is_null($nombre))
            {

				if(!self::validarLongitudDeCadena($nombre, 0, 95)){
					throw new InvalidDataException("nombre muy largo");
				}

            }
            
            //No se encontro error, regresa verdadero
            return true;
        }
        
        /*
         * Valida los parametros de la tabla Regla
         */
        private static function ValidarParametrosRegla
        (
                $id_regla = null,
                $nombre = null,
                $id_producto = null,
                $id_clasificacion_producto = null,
                $id_unidad = null,
                $id_servicio = null,
                $id_clasificacion_servicio = null,
                $id_paquete = null,
                $cantidad_minima = null,
                $porcentaje_utilidad = null,
                $utilidad_neta = null,
                $metodo_redondeo = null,
                $margen_min = null,
                $margen_max = null,
                $secuencia = null
                
        )
        {
            //valida que la regla exista
            if(!is_null($id_regla))
            {
                if(is_null(ReglaDAO::getByPK($id_regla)))
                {
                    return "La regla ".$id_regla." no existe";
                }
            }
            
            //valida que el nombre este en rango
            if(!is_null($nombre))
            {
				if(!self::validarLongitudDeCadena($nombre, 0, 100)){
					throw new InvalidDataException("nombre muy largo");
				}

            }
            
            //valida que el producto exista y este activo
            if(!is_null($id_producto))
            {
                $producto = ProductoDAO::getByPK($id_producto);
                if(is_null($producto))
                {
                    return "El producto ".$id_producto." no existe";
                }
                
                if(!$producto->getActivo())
                {
                    return "El producto ".$id_producto." esta inactivo";
                }
            }
            
            //valida que la clasificacion de producto exista y este activa
            if(!is_null($id_clasificacion_producto))
            {
                $clasificacion_producto = ClasificacionProductoDAO::getByPK($id_clasificacion_producto);
                if(is_null($clasificacion_producto))
                {
                    return "La clasificacion de producto ".$id_clasificacion_producto." no existe";
                }
                
                if(!$clasificacion_producto->getActiva())
                {
                    return "La clasificacion de producto ".$id_clasificacion_producto." no esta activa";
                }
            }
            
            //valida que la unidad exista y este activa
            if(!is_null($id_unidad))
            {
                $unidad = UnidadDAO::getByPK($id_unidad);
                if(is_null($unidad))
                {
                    return "La unidad ".$id_unidad." no existe";
                }
                
                if(!$unidad->getActiva())
                {
                    return "La unidad ".$id_unidad." no esta activa";
                }
            }
            
            //valida que el servicio exista y este activo
            if(!is_null($id_servicio))
            {
                $servicio = ServicioDAO::getByPK($id_servicio);
                if(is_null($servicio))
                {
                    return "El servicio ".$id_servicio." no existe";
                }
                
                if(!$servicio->getActivo())
                {
                    return "El servicio ".$id_servicio." no esta activo";
                }
            }
            
            //valida que la clasificacion de servicio exista y este activa
            if(!is_null($id_clasificacion_servicio))
            {
                $clasificacion_servicio = ClasificacionServicioDAO::getByPK($id_clasificacion_servicio);
                if(is_null($clasificacion_servicio))
                {
                    return "La clasificacion de servicio ".$id_clasificacion_servicio." no existe";
                }
                
                if(!$clasificacion_servicio->getActiva())
                {
                    return "La clasificacion de servicio ".$id_clasificacion_servicio." no esta activa";
                }
            }
            
            //valida que el paquete exista y este activo
            if(!is_null($id_paquete))
            {
                $paquete = PaqueteDAO::getByPK($id_paquete);
                if(is_null($paquete))
                {
                    return "El paquete ".$id_paquete." no existe";
                }
                if(!$paquete->getActivo())
                {
                    return "El paquete ".$id_paquete." esta inactivo";
                }
            }
            
            //valida que la cantidad minima este en rango
            if(!is_null($cantidad_minima))
            {
                $e = self::validarNumero($cantidad_minima, 1.8e200, "cantidad minima");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el porcentaje de utilidad este en rango
            if(!is_null($porcentaje_utilidad))
            {
                $e = self::validarNumero($porcentaje_utilidad, 1, "porcentaje_utilidad",-1);
                if(is_string($e))
                    return $e;
            }
            
            //valida que la utilidad neta este en rango
            if(!is_null($utilidad_neta))
            {
                $e = self::validarNumero($utilidad_neta, 1.8e200, "Utilidad neta", -1.8e200);
                if(is_string($e))
                    return $e;
            }
            
            //valida que el metodo de redondeo este en rango
            if(!is_null($metodo_redondeo))
            {
                $e = self::validarNumero($metodo_redondeo, 1.8e200, "metodo_redondeo");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el margen minimo este en rango
            if(!is_null($margen_min))
            {
                $e = self::validarNumero($margen_min, 1.8e200, "margen minimo");
                if(is_string($e))
                    return $e;
            }
            
            //valida que el margen maximo este en rango
            if(!is_null($margen_max))
            {
                $e = self::validarNumero($margen_max, 1.8e200, "margen maximo");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la secuencia este en rango
            if(!is_null($secuencia))
            {
                $e = self::validarNumero($secuencia, PHP_INT_MAX, "secuencia");
                if(is_string($e))
                    return $e;
            }
            
            //no se encontro error, regresa verdadero
            return true;
        }
        
        /*
         * Cambia la version activa de una tarifa
         */
        private static function setVersionActiva
        (
                $id_version
        )
        {
            Logger::log("Cambiando version activa por la version ".$id_version);
            
            //Se valida la version recibida
            $validar = self::ValidarParametrosVersion($id_version);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            $version = VersionDAO::getByPK($id_version);
            
            //Se valida la tarifa de la version
            $id_tarifa = $version->getIdTarifa();
            $validar = self::ValidarParametrosTarifa($id_tarifa);
            if(is_string($validar))
            {
                Logger::error("FATAL!!!! ".$validar);
                throw new Exception("FATAL!!!! ".$validar,901);
            }
            $tarifa = TarifaDAO::getByPK($id_tarifa);
            
            $versiones_activas = VersionDAO::search( new Version( array( "id_tarifa" => $id_tarifa, "activa" => 1 ) ) );
            
            //Si existe mas de una version activa, se manda error fatal pues ese caso no deberia de ocurrir
            if(count($versiones_activas)>1)
            {
                Logger::error("FATAL!!!! Se encontro mas de una version activa en la tarifa ".$id_tarifa);
                throw new Exception("FATAL!!!! Se encontro mas de una version activa en la tarifa ".$id_tarifa,901);
            }
            
            $version_activa = null;
            //Puede darse el caso que no exista ninguna version activa aun.
            if(count($versiones_activas)==1)
            {
                //Se da por hecho que solo hay una version activa por tarifa
                $version_activa = $versiones_activas[0];
                $version_activa->setActiva(0);
            }
            $version->setActiva(1);
            $tarifa->setIdVersionActiva($version->getIdVersion());
            DAO::transBegin();
            try
            {
                if(!is_null($version_activa))
                {
                    VersionDAO::save($version_activa);
                }
                VersionDAO::save($version);
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transBegin();
                Logger::error("No se pudo cambiar la version activa: ".$e);
                throw new Exception("No se pudo cambiar la version activa",901);
            }
            DAO::transEnd();
            Logger::log("Version activa cambiada exitosamente");
        }
  
	/**
 	 *
 	 *Asigna el default a una tarifa de compra. La tarifa default es la que se usara en todas las compras a menos que el usuario indique otra tarifa.

Solo se puede elegir una tarifa de tipo compra.
 	 *
 	 * @param id_tarifa int Id de la tarifa de compra a elegir como default del sistema
 	 **/
	private static function CompraSetDefaultTarifa
	(
		$id_tarifa
	)
	{  
            Logger::log("Cambiando la taifa de compra default del sistema por la tarifa ".$id_tarifa);
            
            //Se valida la tarifa recibida
            $validar = self::ValidarParametrosTarifa($id_tarifa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Valida que la tarifa recibida sea de compra
            $tarifa = TarifaDAO::getByPK($id_tarifa);
            if($tarifa->getTipoTarifa()!="compra")
            {
                Logger::error("La tarifa ".$id_tarifa." no es de compra");
                throw new Exception("La tarifa ".$id_tarifa." no es de compra",901);
            }
            
            //Se busca la tarifa default del sistema y se desactiva su bandera
            $tarifas_default = TarifaDAO::search( new Tarifa( array( "activa" => 1, "default" => 1, "tipo_tarifa" => "compra" ) ) );
            
            //---------------------------------
            
            //Si se encuentra mas de una default o ninguna default, mandar un error fatal, pues no deberia darse este caso.
            /*if(count($tarifas_default)!=1)
            {
                Logger::error("FATAL!!! No se encontro o se encontro mas de una tarifa de compra por default en el sistema");
                throw new Exception("FATAL!!! No se encontro o se encontro mas de una tarifa de compra por default en el sistema",901);
            }
            
            //Se da por hecho que solo hay una tarifa de compra por default en el sistema 
            $tarifa_default = $tarifas_default[0];
            $tarifa_default->setDefault(0);*/
            
            
            DAO::transBegin();
            
            foreach($tarifas_default as $t){
                
                $t->setDefault(0);
                
                try
                {
                    TarifaDAO::save($t);                    
                }
                catch(Exception $e)
                {
                    DAO::transRollback();
                    Logger::error("No se pudo cambiar la tarifa de compra por default del sistema: ".$e);
                    throw new Exception("No se pudo cambiar la tarifa de compra por default del sistema. Intentelo mas tarde o consulte a su administrador del sistema",901);
                }
            }
            
            //---------------------------------------
            
            $tarifa->setDefault(1);
           
            try
            {
                //TarifaDAO::save($tarifa_default);
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo cambiar la tarifa de compra por default del sistema: ".$e);
                throw new Exception("No se pudo cambiar la tarifa de compra por default del sistema. Intentelo mas tarde o consulte a su administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa de compra por default en el sistema cambiada exitosamente. Ahora es ".$id_tarifa);
	}
  
	/**
 	 *
 	 *Crea una nueva tarifa 
 	 *
 	 * @param id_moneda int Id de la moneda en la que se manejaran los valores de precios en esta tarifa
 	 * @param nombre string nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" y sirve para identificar si la tarifa se aplicara en compras o en ventas
 	 * @param activa bool Si esta tarifa estara activa al momento de su creacion
 	 * @param default bool Si se quiere que esta tarifa sea la default del sistema
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	private static function NuevaTarifaBase
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$activa = null, 
		$default = null
	)
	{  
            Logger::log("creando nueva tarifa");
            
            //Se validan los parametros recibidos
            $validar = self::ValidarParametrosTarifa(null, $nombre, $tipo_tarifa, $id_moneda);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Si no se recibe parametro activa, se dara por hecho que es 1
            if(is_null($activa))
            {
                $activa = 1;
            }
            
            $tarifa = new Tarifa( 
                    array(
                            "id_moneda"     => $id_moneda,
                            "nombre"        => $nombre,
                            "tipo_tarifa"   => $tipo_tarifa,
                            "activa"        => $activa,
                            "default"       => 0
                    ) 
                    );
            DAO::transBegin();
            try
            {
                TarifaDAO::save($tarifa);
                //Si se recibio que esta tarifa fuera la default del sistema, entonces se llama a los metodos encargados de cambiar el default.
                if($default)
                {
                    switch($tipo_tarifa)
                    {
                        case "compra" : self::CompraSetDefaultTarifa($tarifa->getIdTarifa());
                            break;
                        case "venta" :self::VentaSetDefaultTarifa($tarifa->getIdTarifa());
                            break;
                    }
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la tarifa nueva: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear la tarifa nueva: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear la tarifa nueva, intente mas tarde o contacte a su administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa creada exitosamente, se obtuvo el id ".$tarifa->getIdTarifa());
            return $tarifa->getIdTarifa();
  
	}
  
	/**
 	 *
 	 *Edita la informacion b?sica de una tarifa, su nombre, su tipo de tarifa o su moneda. Si se edita el tipo de tarifa se tiene que verificar que esta tarifa no este siendo usada por default en una tarifa de su tipo anterior. 

Ejemplo: La tarifa 1 es tarifa de compra, el usuario 1 tiene como default de tarifa de compra la tarifa 1. Si queremos editar el tipo de tarifa de la tarifa 1 a una tarifa de venta tendra que mandar error, especificando que la tarifa esta siendo usada como tarifa de compra por el usuario 1.

Los parametros que no sean explicitamente nulos seran tomados como edicion.
 	 *
 	 * @param id_tarifa int Id de la tarifa que se va a editar
 	 * @param id_moneda int Id de la moneda con la que se manejaran las operaciones de esta tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera usada en las operaciones de compra o de venta
 	 **/
	private static function EditarTarifaBase
	(
		$id_tarifa, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	)
	{  
            Logger::log("Editando la tarifa ".$id_tarifa);
            
            $validar = self::ValidarParametrosTarifa($id_tarifa,$nombre,$tipo_tarifa,$id_moneda);
            
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Se actualizan los datos que no sean nulos
            $tarifa = TarifaDAO::getByPK($id_tarifa);
            if(!is_null($id_moneda))
            {
                $tarifa->setIdMoneda($id_moneda);
            }
            if(!is_null($nombre))
            {
                $tarifa->setNombre($nombre);
            }
            if(!is_null($tipo_tarifa))
            {
                //Si cambia el tipo de tarifa, se verifica que no se este editando el default,
                //pues este no puede ser cambiado mientras sea default. 
                if($tipo_tarifa!=$tarifa->getTipoTarifa())
                {
                    if( $tarifa->getDefault() )
                    {
                        Logger::error("Se busca cambiar el tipo de tarifa a la tarifa default");
                        throw new Exception("Se busca cambiar el tipo de tarifa a la tarifa default",901);
                    }
                    $tarifa->setTipoTarifa($tipo_tarifa);
                }
            }
            DAO::transBegin();
            try
            {
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la tarifa ".$e);
                throw new Exception("No se pudo editar la tarifa",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa editada exitosamente");
  
	}
  
	/**
 	 *
 	 *Duplica la version obtenida junto con todas sus reglas y la guarda en otra tarifa. Este metodo sirve cuando una misma version con todas sus reglas aplica a mas de una tarifa.

Al duplicar una version, las reglas duplicadas con ella actualizan su id de la version a la nueva version creada.

Cuando una version activa y/o default se duplica, al guardarse en la otra tarifa pierde estas propiedades.
 	 *
 	 * @param id_tarifa int Id de la tarifa en la que se guardara la version con todas sus reglas
 	 * @param id_version int Id de la version a duplicar
 	 * @return id_version int Id de la version creada
 	 **/
	private static function DuplicarVersion
	(
		$id_tarifa, 
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Selecciona como default para las ventas una tarifa de venta. Esta tarifa sera usada para todas las ventas a menos que el usuario indique otra tarifa de venta.

Solo puede asignarse como default de ventas una tarifa de tipo venta
 	 *
 	 * @param id_tarifa int Id de la tarifa a poner como default
 	 **/
	private static function VentaSetDefaultTarifa
	(
		$id_tarifa
	)
	{  
            Logger::log("Cambiando la taifa de venta default del sistema por la tarifa ".$id_tarifa);
            
            //Se valida la tarifa recibida
            $validar = self::ValidarParametrosTarifa($id_tarifa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Valida que la tarifa recibida sea de venta
            $tarifa = TarifaDAO::getByPK($id_tarifa);
            if($tarifa->getTipoTarifa()!="venta")
            {
                Logger::error("La tarifa ".$id_tarifa." no es de venta");
                throw new Exception("La tarifa ".$id_tarifa." no es de venta",901);
            }
            
            //Se busca la tarifa default del sistema y se desactiva su bandera
            $tarifas_default = TarifaDAO::search( new Tarifa( array( "activa" => 1, "default" => 1, "tipo_tarifa" => "venta" ) ) );
            
            //Si se encuentra mas de una default o ninguna default, mandar un error fatal, pues no deberia darse este caso.
            if(count($tarifas_default)!=1)
            {
                Logger::error("FATAL!!! No se encontro o se encontro mas de una tarifa de venta por default en el sistema");
                throw new Exception("FATAL!!! No se encontro o se encontro mas de una tarifa de venta por default en el sistema",901);
            }
            
            //Se da por hecho que solo hay una tarifa de venta por default en el sistema 
            $tarifa_default = $tarifas_default[0];
            $tarifa_default->setDefault(0);
            
            
            $tarifa->setDefault(1);
            
            DAO::transBegin();
            try
            {
                TarifaDAO::save($tarifa_default);
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo cambiar la tarifa de venta por default del sistema: ".$e);
                throw new Exception("No se pudo cambiar la tarifa de venta por default del sistema. Intentelo mas tarde o consulte a su administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa de venta por default en el sistema cambiada exitosamente. Ahora es ".$id_tarifa);
	}
  
	/**
 	 *
 	 *Edita la informacion basica de una version. El nombre, la fecha de inicio y la fecha de fin.

?Sera necesario permitir que el usuario cambie una version de una tarifa a otra tarifa?
 	 *
 	 * @param id_version int Id de la version a editar
 	 * @param fecha_fin string Fecha a aprtir de la cual se dejaran de aplicar las reglas de esta version. Si esta fecha ya paso se aplicaran las reglas de la version default de la tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si esta fecha aun no llega, se aplicaran las reglas de la version default de la tarifa.
 	 * @param nombre string Nombre de la version
 	 **/
	private static function EditarVersion
	(
		$id_version, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$nombre = null
	)
	{  
            Logger::log("Editando version ".$id_version);
            
            //Se valida la version recibida
            $validar = self::ValidarParametrosVersion($id_version,$nombre);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            //Los parametros recibidos seran tomados como actualizacion
            $version = VersionDAO::getByPK($id_version);
            
            if(!is_null($fecha_fin))
            {
                $version->setFechaFin($fecha_fin);
            }
            if(!is_null($fecha_inicio))
            {
                $version->setFechaInicio($fecha_inicio);
            }
            if(!is_null($nombre))
            {
                $version->setNombre($nombre);
            }
            
            //Se verifica que haya dos fechas en el objeto
            if( is_null($version->getFechaInicio()) XOR is_null($version->getFechaFin()))
            {
                if(is_null($version->getFechaInicio()))
                {
                    $version->setFechaInicio(date("Y-m-d H:i:s"));
                }
                else
                {
                    $version->setFechaFin("9999-12-31 23:59:59");
                }
            }
            DAO::transBegin();
            try
            {
                VersionDAO::save($version);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la version ".$e);
                throw new Exception("No se pudo editar la version ",901);
            }
            DAO::transEnd();
            Logger::log("Version editada exitosamente");
	}
  
	/**
 	 *
 	 *Activa una version. Como solo puede haber una version activa por tarifa, este metodo desactiva la version actualmente activa de la tarifa y activa la version obtenida como parametro.
 	 *
 	 * @param id_version int Id de la version a activar
 	 **/
	private static function ActivarVersion
	(
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Pone como default a la version obtenida para esta tarifa. Solo puede haber una version default por tarifa, asi que este metodo le quita el default a la version que lo era anteriormente y lo pone en la version obtenida como parametro.

>>>>>>> .r2127
Una version default no puede caducar.
 	 *
 	 * @param id_version int Id de la version a la que se le dara el default
 	 **/
	private static function SetDefaultVersion
	(
		$id_version
	)
	{  
            Logger::log("Cambiando version default por la version ".$id_version);
            
            //Se valida la version recibida
            $validar = self::ValidarParametrosVersion($id_version);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar,901);
            }
            
            $version = VersionDAO::getByPK($id_version);
            
            //Si la version recibida tiene definido una fecha de inicio o una fecha de fin, entonces no puede ser default
            //pues esto implica que caducara
            if( !is_null($version->getFechaFin()) || !is_null($version->getFechaInicio()) )
            {
                Logger::error("La version que se intenta hacer default es una con caducidad, las versiones default no pueden tener caducidad");
                throw new Exception("La version que se intenta hacer default es una con caducidad, las versiones default no pueden tener caducidad",901);
            }
            
            //Se valida la tarifa de la version
            $id_tarifa = $version->getIdTarifa();
            $validar = self::ValidarParametrosTarifa($id_tarifa);
            if(is_string($validar))
            {
                Logger::error("FATAL!!!! ".$validar);
                throw new Exception("FATAL!!!! ".$validar,901);
            }
            $tarifa = TarifaDAO::getByPK($id_tarifa);

            //---------------------------------------------------------------------
            
            $versiones_default = VersionDAO::search( new Version( array( "id_tarifa" => $id_tarifa, "default" => 1 ) ) );
                        
            
            //Si existe mas de una version default, se manda error fatal pues ese caso no deberia de ocurrir
            /*if(count($versiones_default)>1)
            {
                Logger::error("FATAL!!!! Se encontro mas de una version default en la tarifa ".$id_tarifa);
                throw new Exception("FATAL!!!! Se encontro mas de una version default en la tarifa ".$id_tarifa,901);
            }*/
            
            $version_default = null;
            //Puede darse el caso que no exista ninguna version default aun.
            if(count($versiones_default)==1)
            {
                //Se da por hecho que solo hay una version default por tarifa
                $version_default = $versiones_default[0];
                $version_default->setDefault(0);
            }
            $version->setDefault(1);
            $tarifa->setIdVersionDefault($version->getIdVersion());                        
            
            
            //-----------------------------------------------------------------------
            
            
            DAO::transBegin();
            try
            {
                if(!is_null($version_default))
                {
                    VersionDAO::save($version_default);
                }
                VersionDAO::save($version);
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transBegin();
                Logger::error("No se pudo cambiar la version default: ".$e);
                throw new Exception("No se pudo cambiar la version default",901);
            }
            DAO::transEnd();
            Logger::log("Version default cambiada exitosamente");
            
	}
  
	/**
 	 *
 	 *Elimina una version permamentemente de la base de datos junto a todas sus reglas.

La version default de la tarifa no puede ser eliminada ni la version activa.

La version por default de la tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_version int Id de la version a eliminar
 	 **/
	private static function EliminarVersion
	(
		$id_version
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una nueva version para una tarifa.

Si no se reciben fechas de inicio o fin, se da por hecho que la version no caduca. Si solo se recibe fecha de fin, se toma como la fecha de inicio la fecha actual del servidor. Si solo se recibe fecha de inicio, se toma como fecha final la maxima permitida por MySQL (9999-12-31 23:59:59).

La version por default de una tarifa no puede caducar.

Las tarifas solo pueden tener una version activa.
 	 *
 	 * @param id_tarifa int Id de la tarifa a la cual pertenece esta version
 	 * @param nombre string Nombre de la version
 	 * @param activa bool Determina si esta version sera la version activa para esta tarifa
 	 * @param default bool Si esta sera la version por default de esta tarifa. Una version por default no puede caducar
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las reglas de esta version. Cuando pase esta fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las reglas de esta version. Si aun no llega etsa fecha, se usaran las reglas de la version por default de esta tarifa
 	 * @return id_version int Id de la version creada
 	 **/
	private static function NuevaVersion
	(
		$id_tarifa, 
		$nombre, 
		$activa = null, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null
	)
	{  
            Logger::log("Creando nueva version ");
            
            //Se validan los parametros recibidos, como este es un metodo interno, se da por hecho que la tarifa siempre sera valida
            $validar = self::ValidarParametrosVersion(null, $nombre);
            
            //Si una de las fechas es nula, pero no ambas, se hace una sobreescritura
            if( is_null($fecha_inicio) XOR is_null($fecha_fin))
            {
                if(is_null($fecha_inicio))
                {
                    $fecha_inicio = date("Y-m-d H:i:s");
                }
                else
                {
                    $fecha_fin = "9999-12-31 23:59:59";
                }
            }
            
            $version = new Version( 
                    array(
                            "id_tarifa"     => $id_tarifa,
                            "nombre"        => $nombre,
                            "activa"        => 0,
                            "default"       => 0,
                            "fecha_fin"     => $fecha_fin,
                            "fecha_inicio"  => $fecha_inicio
                    ) 
                    );
            
            DAO::transBegin();
            try
            {
                VersionDAO::save($version);
                if($default)
                {
                    self::SetDefaultVersion($version->getIdVersion());
                }
                if($activa)
                {
                    self::setVersionActiva($version->getIdVersion());
                }
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva version: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear la nueva version: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear la nueva version, intente de nuevo mas tarde o consulte a su administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Version creada exitosamente");
            return $version->getIdVersion();
	}
  
	/**
 	 *
 	 *Crea una nueva regla para una version. 

Una regla que no tiene producto, categoria de producto o alguna otra relacion, es una regla que se aplica a todos los productos, servicios y paquetes.

Las secuencias de las reglas no se pueden repetir.

La formula que siguen las reglas para obtener el precio fina es la siguiente: 

       Precio Final = Precio Base * (1 + porcentaje_utilidad) + utilidad_neta

Donde :
 
    Precio Base : Es obtenido de la tarifa con la que se relaciona esta regla. 
                  Si no se relaciona con ninguna tarifa, entonces lo toma del 
                  precio o costo (dependiendo del metodo de costeo) del producto,servicio
                  o paquete.

    porcentaje_utilidad:El porcentaje de utilidad que se le ganara al precio o costo base.
                        Puede ser negativo

    utilidad_neta: La utilidad neta que se ganara al comerciar este producto,servicio o
                   paquete. Puede ser negativo.


Al asignar una tarifa base a una regla se verifica que no haya una dependencia circular.

Una misma regla puede aplicar a un producto, una clasificacion de producto, un servicio, una clasificacion de servicio y un paquete a la vez.
 	 *
 	 * @param id_version int Id de la version a la que pertenecera esta regla
 	 * @param nombre string Nombre de la regla
 	 * @param secuencia int Numero de secuencia de la regla, sirve para definir prioridades entre las reglas.
 	 * @param cantidad_minima int Cantidad minima que debe cumplirse de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la clasificacion del producto a la que se le aplicara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion del servicio a la cual se le aplicara esta regla
 	 * @param id_paquete int Id del paquete al cal se le aplicara esta regla
 	 * @param id_producto int Id del producto al que se le aplicara esta regla
 	 * @param id_servicio int Id del servicio al cual se le aplicara esta regla
 	 * @param id_tarifa int Id de la tarifa que se usara para determinar el precio base del objeto
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Pendiente descripcion por Manuel
 	 * @param margen_min float Pendiente descripcion por Manuel
 	 * @param metodo_redondeo float Pendiente descricpion por manuel
 	 * @param porcentaje_utilidad float Porcentaje de utilidad, va de -1 a 1
 	 * @param utilidad_neta float La utilidad neta que se ganara, puede ser negativa indicando un descuento
 	 * @return id_regla int Id de la regla creada
 	 **/
	private static function NuevaRegla
	(
		$id_version, 
		$nombre, 
		$secuencia, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = 0, 
		$margen_min = 0, 
		$metodo_redondeo = 0, 
		$porcentaje_utilidad = 0, 
		$utilidad_neta = 0
	)
	{  
            Logger::log("Creando nueva regla");
            
            //Se validan los parametros recibidos
            $validar = self::ValidarParametrosRegla(null, $nombre, $id_producto, 
                    $id_clasificacion_producto, $id_unidad, $id_servicio, $id_clasificacion_servicio, 
                    $id_paquete, $cantidad_minima, $porcentaje_utilidad, $utilidad_neta, $metodo_redondeo,
                    $margen_min, $margen_max, $secuencia);
            
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Valida que la secuencia de la regla no exista ya en esta version
            $reglas = ReglaDAO::search(new Regla( array( "id_version" => $id_version ) ));
            //var_dump($reglas);
            foreach($reglas as $regla)
            {
                if($regla->getSecuencia()==$secuencia)
                {
                    Logger::error("La secuencia ".$secuencia." ya esta en uso por la regla ".$regla->getIdRegla());
                    throw new Exception("La secuencia ".$secuencia." ya esta en uso por la regla ".$regla->getIdRegla(),901);
                }
            }
            
            
            
            $regla = new Regla( 
                    array( 
                            "id_version"                => $id_version,
                            "nombre"                    => $nombre,
                            "id_producto"               => $id_producto,
                            "id_clasificacion_producto" => $id_clasificacion_producto,
                            "id_clasificacion_servicio" => $id_clasificacion_servicio,
                            "id_unidad"                 => $id_unidad,
                            "id_servicio"               => $id_servicio,
                            "id_paquete"                => $id_paquete,
                            "cantidad_minima"           => $cantidad_minima,
                            "porcentaje_utilidad"       => $porcentaje_utilidad,
                            "utilidad_neta"             => $utilidad_neta,
                            "metodo_redondeo"           => $metodo_redondeo,
                            "margen_min"                => $margen_min,
                            "margen_max"                => $margen_max,
                            "secuencia"                 => $secuencia,
                            "id_tarifa"                 => ($id_tarifa ? $id_tarifa : -1)
                    ) 
                    );
            
            DAO::transBegin();
            try
            {
                ReglaDAO::save($regla);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la nueva regla: ".$e);
                throw new Exception("No se pudo crear la nueva regla, intenelo mas tarde o consulte a su administrador del sistema",901);
            }
            DAO::transEnd();
            Logger::log("La regla ha sido creada exitosamente");
  
	}
  
	/**
 	 *
 	 *Lista las versiones existentes, se puede filtrar por la tarifa y ordenar por los atributos de al tabla
 	 *
 	 * @param id_tarifa int Si este valor es obtenido, se listaran las versiones pertenecientes a esta tarifa
 	 * @param orden string nombre de al columna por la cual sera ordenada l alista
 	 * @return lista_versiones json Arreglo de versiones
 	 **/
	private static function ListaVersion
	(
		$id_tarifa = null, 
		$orden = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las tarifas existentes. Se puede ordenar de acuerdo a los atributos de la tabla y se puede filtrar por el tipo de tarifa, la moneda que usa y por el valor de activa.
 	 *
 	 * @param activa bool Si este valor es obtenido, se listaran las tarifas que cuyo valor de activa sea como el obtenido
 	 * @param id_moneda int Si es obtenido este valor, se listaran las tarifas que tengan el valor de moneda como el obtenido.
 	 * @param orden string El nombre de la columna de la tabla por la cual se ordenara la lista
 	 * @param tipo_tarifa string Si es obtenido, se listaran las tarifas que tengan el mismo valor de tipo de tarifa que este.
 	 * @return lista_tarifas json Arreglo de tarifas
 	 **/
	private static function ListaTarifa
	(
		$activa = null, 
		$id_moneda = null, 
		$orden = null, 
		$tipo_tarifa = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Duplica una tarifa con todas sus versiones, y cada una de ellas con todas sus reglas. Este metodo sirve cuando se tiene una tarifa muy completa y se quiere hacer una tarifa muy similar pero con unas ligeras modificaciones.

Al duplicar la tarifa, se actualizan sus versiones default y activa por los ids generados al duplicar las versiones.

La tarifa duplicada pierde ela tributo default.
 	 *
 	 * @param id_moneda int Id de la moneda que aplicara para la nueva tarifa
 	 * @param id_tarifa int Id de la tarifa a duplicar
 	 * @param nombre string Nombre de la nueva tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la nueva tarifa sera aplicada para compras o ventas
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	private static function DuplicarTarifa
	(
		$id_moneda, 
		$id_tarifa, 
		$nombre, 
		$tipo_tarifa
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion basica de una regla. 

Los parametros recibidos seran tomados para edicion.

?Sera necesario dar la oportunidad al usuario de cambiar la version a la que pertence la regla?
 	 *
 	 * @param id_regla int Id de la regla a editar
 	 * @param cantidad_minima int Cantidad minima de objetos para que esta regla se cumpla
 	 * @param id_clasificacion_producto int Id de la categoria del producto sobre la cual actuara esta regla
 	 * @param id_clasificacion_servicio int Id de la clasificacion de servicio sobre la cual actuara esta regla
 	 * @param id_paquete int Id del paquete sobre el cual actuara esta regla
 	 * @param id_producto int Id del producto sobre el cual actuara esta regla
 	 * @param id_servicio int Id del servicio sobre el cual actuara esta regla
 	 * @param id_tarifa int Id de la tarifa de donde se obtendra el precio base
 	 * @param id_unidad int La regla se aplicara a los productos (especificados por el id_producto o id_clasificacion_producto) que esten en esta unidad. Si un id de producto no ha sido especificado, este valor se ignora.
 	 * @param margen_max float Falta definir por Manuel
 	 * @param margen_min float Falta definir por Manuel
 	 * @param metodo_redondeo float Falta definir por Manuel
 	 * @param nombre string Nombre del usuario
 	 * @param porcentaje_utilidad float Porncetaje de utilidad que se ganara al comercia con este objeto
 	 * @param secuencia int Numero de secuencia de esta regla
 	 * @param utilidad_neta float Utilidad neta que s eganara al comerciar con este objeto
 	 **/
	private static function EditarRegla
	(
		$id_regla, 
		$cantidad_minima = null, 
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$margen_max = null, 
		$margen_min = null, 
		$metodo_redondeo = null, 
		$nombre = null, 
		$porcentaje_utilidad = null, 
		$secuencia = null, 
		$utilidad_neta = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Elimina una regla. La regla por default de l aversion por default de la tarifa por default no puede ser eliminada
 	 *
 	 * @param id_regla int Id de la regla a eliminar
 	 **/
	private static function EliminarRegla
	(
		$id_regla
	)
	{  
            Logger::log("Eliminando la regla ".$id_regla);
            
            //Valida que la regla exista
            $validar = self::ValidarParametrosRegla($id_regla);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $regla = ReglaDAO::getByPK($id_regla);
            
            DAO::transBegin();
            try
            {
                ReglaDAO::delete($regla);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar la regla");
                throw new Exception("No se pudo eliminar la regla",901);
            }
            DAO::transEnd();
            Logger::log("Regla eliminada exitosamente");
	}
  
	/**
 	 *
 	 *Lista las reglas existentes. Puede filtrarse por la version, por producto, por unidad, por categoria de producto o servicio, por servicio o por paquete, por tarifa base o por alguna combinacion de ellos.
 	 *
 	 * @param id_clasificacion_producto int Si se recibe este parametro, se lsitaran las reglas que afectan a esta clasificacion de producto
 	 * @param id_clasificacion_servicio int Si se recibe este parametro, se listaran las reglas que afecten a esta clasificacion de servicio
 	 * @param id_paquete int Si se recibe este parametro, se listaran las reglas que afecten a este paquete
 	 * @param id_producto int Si se recibe este parametro se listaran las reglas que afectan a este producto
 	 * @param id_servicio int Si se recibe este parametro se listaran las reglas que afecten a este servicio
 	 * @param id_tarifa int Si se recibe este parametro, se listaran las reglas que se basen en esta tarifa
 	 * @param id_unidad int Si se recibe este parametro, se listaran las reglas que afecten a esta unidad
 	 * @param id_version int Si se obtiene este parametro se listaran las relas de esta version
 	 * @return lista_reglas int Arreglo de reglas
 	 **/
	private static function ListaRegla
	(
		$id_clasificacion_producto = null, 
		$id_clasificacion_servicio = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null, 
		$id_version = null
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Duplica una regla y la guarda en otra version. Las reglas duplicadas actualizan el id de la version a la que pertenecen y mantienen todos sus datos.
 	 *
 	 * @param id_regla int Id de la regla a duplicar
 	 * @param id_version int Id de la version a la cual se duplicara la regla
 	 * @return id_regla int Id de la regla creada
 	 **/
	private static function DuplicarRegla
	(
		$id_regla, 
		$id_version
	)
	{  
  
  
	}
          
        
        /**
 	 *
 	 *Calcula el precio de un producto. Se calcula un precio por tarifa activa en el sistema y al final se regresa un arreglo con las tarifas y su respectivo precio.

El precio es calculado a partir de las reglas de una tarifa.
 	 *
 	 * @param id_producto int Id del producto al cual se le desea calcular su precio
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si los precios a calcular seran en base a tarifas de compra o de venta
 	 * @param cantidad float Cantidad de producto a calcular su precio. Pues existen algunas reglas que aplican solo si hay una cierta cantidad de producto
 	 * @return precios json Arreglo de tarifas con sus respectivos precios
 	 **/
	public static function CalcularPorArticulo
	(
		$tipo_tarifa, 
		$cantidad = null, 
		$id_paquete = null, 
		$id_producto = null, 
		$id_servicio = null, 
		$id_tarifa = null, 
		$id_unidad = null
	)
	{  
            //Deprecated
            //
            //
//            Logger::log("Calculando precio de ".$tipo_tarifa."  para el producto ".$id_producto." o el servicio ".$id_servicio." o el paquete ".$id_paquete);
//            
//            //si no se recibe ningun articulo, se regresa un error
//            if(is_null($id_producto)&&is_null($id_servicio)&&  is_null($id_paquete))
//            {
//                Logger::error("No se sabe si se calculara el precio de un producto, un servicio o un paquete");
//                throw new Exception("No se sabe si se calculara el precio de un producto, un servicio o un paquete",901);
//            }
//            
//            //Se valida que el tipo de tarifa sea valido
//            if($tipo_tarifa!="compra" && $tipo_tarifa!="venta")
//            {
//                Logger::error("El tipo de tarifa (".$tipo_tarifa.") es invalido, tiene que ser 'compra' o 'venta'");
//                throw new Exception("El tipo de tarifa (".$tipo_tarifa.") es invalido, tiene que ser 'compra' o 'venta'",901);
//            }
//            
//            //Valida la cantidad recibida
//            $validar = self::validarNumero($cantidad, 1.8e200, "cantidad");
//            if(is_string($validar))
//            {
//                Logger::error($validar);
//                throw new Exception($validar,901);
//            }
//            
//            if(!is_null($id_producto))
//            {
//                $validar = self::validarProducto($id_producto, $id_unidad, $tipo_tarifa);
//                if(is_string($validar))
//                {
//                    Logger::error($validar);
//                    throw new Exception($validar,901);
//                }
//            }
//            else if(!is_null($id_servicio))
//            {
//                $validar = self::validarServicio($id_servicio);
//                if(is_string($validar))
//                {
//                    Logger::error($validar);
//                    throw new Exception($validar,901);
//                }
//            }
//            else if(!is_null($id_paquete))
//            {
//                $validar = self::validarPaquete($id_paquete);
//                if(is_string($validar))
//                {
//                    Logger::error($validar);
//                    throw new Exception($validar,901);
//                }
//            }
//            
//            //Se obtienen las reglas de cada tarifa
//            
//            $reglas = TarifaDAO::obtenerTarifa();
//
//            //Se calcula cada precio de acuerdo a las reglas obtenidas y se almacena en el arreglo final
//
//            $precios = array();
//
//            foreach($reglas as $regla)
//            {
//                $precio = array();
//
//                $precio["id_tarifa"] = $regla["id_tarifa"];
//                foreach($regla["reglas"] as $r)
//                {
//                    if($r->getIdTarifa()==-1)
//                    {
//                        if(!is_null($id_producto))
//                        {
//                            $producto = ProductoDAO::getByPK($id_producto);
//                            if($producto->getMetodoCosteo()=="costo")
//                            {
//                                $precio_base = $producto->getCostoEstandar();
//                            }
//                            else if($producto->getMetodoCosteo()=="precio")
//                            {
//                                $precio_base = $producto->getPrecio();
//                            }
//                            else
//                            {
//                                Logger::error("El producto tiene un metodo de costeo invalido");
//                                throw new Exception("El producto tiene un metodo de costeo invalido", 901);
//                            }
//                        }
//                        else if(!is_null($id_servicio))
//                        {
//                            $servicio = ServicioDAO::getByPK($id_servicio);
//                            if($servicio->getMetodoCosteo()=="costo")
//                            {
//                                $precio_base = $servicio->getCostoEstandar();
//                            }
//                            else if($servicio->getMetodoCosteo()=="precio")
//                            {
//                                $precio_base = $servicio->getPrecio();
//                            }
//                            else
//                            {
//                                Logger::error("El servicio tiene un metodo de costeo invalido");
//                                throw new Exception("El servicio tiene un metodo de costeo invalido", 901);
//                            }
//                        }
//                        else
//                        {
//                            $paquete = PaqueteDAO::getByPK($id_paquete);
//                            $precio_base = $paquete->getPrecio();
//                        }
//                    }
//                    else
//                    {
//                        
//                    }
//                }
//
//            }
            
            
	}
  
	/**
 	 *
 	 *Crea una nueva tarifa que le dara un precio especial a todos los productos, servicios y paquetes o solo a algunos. 

Una tarifa puede tener fechas de inicio y de fin que indican en que fechas se tomaran sus parametros. Si no se reciben fechas, se da por hecho que la tarifa no caduca. Si solo se recibe fecha de inicio, se toma como fecha de fin la maxima fecha permitida por MySQL (9999-12-31 23:59:59). Si solo se recibe fehca de fin, se toma como fecha de inicio la fecha actual del servidor.

Una tarifa puede afectar a uno o varios productos, servicios, clasificaciones de producto, clasificaciones de servicio, unidades, y/o paquetes; cada uno con los parametros de la siguiente funcion:

   Precio Final : Precio Base * (1 + porcentaje_utilidad) + utilidad_neta


Donde:


   Precio Base: Sera obtenido de la tarifa base de esta tarifa.

   porcentaje_utilidad: porcentaje de -1 a 1 de lo que se le ganara del precio base a esta tarifa.

   utilidad_neta: Ganancia neta para esta tarifa del precio base. Puede ser negativa implicando un descuento.

   Precio Final: El resultado de la formula, este valor puede ser afectado directamente por el usuario mediante los parametros metodo_redondeo, margen_min y margen_max. 

   metodo_redondeo: Es el multiplo con el cual se redondea el Precio Base despues de aplicar el porcentaje de utilidad y antes de sumar la utilidad neta. Si se quiere que todos los productos terminen en 9.99, entonces se configura el metodo_redondeo en 10 y la utilidad_neta en -0.01.

   margen_min: Es el Precio Final m?nimo permitido, si despues de realizar todos los calculos, el precio final resulta menor al valor de margen_min, se sobreecribe y se toma el valor de margen_min.

   margen_max: Es el Precio Final maximo permitido, si despues de realizar todos los calculos, el precio final resulta mayor al valor de margen_max, se sobreescribe y se toma el valor de margen_max.
   


Si no se recibe un producto, servicio, clasificacion de producto o servicio, unidad o paquete junto a estos parametros, se toma que afectara a todos los productos, servicios, clasificaciones, unidades y paquetes.

Si se recibe un producto sin unidad, entonces los parametros afectan a todos los productos sin importar su unidad, si solo se recibe una unidad sin productos, es ignorada y se toma la tarifa como que afecta a todos los productos, servicios, clasificaciones, etc.

NOTA: Se debe de tener cuidad al configurar el margen_min y margen_max pues si estos se aplican sin especificar un producto, servicio, clasificacion de producto o servicio, o paquete, aplicaran a todos los productos, servicios y paquetes.

La asignacion de una formula a algun producto, servicio, etc. requiere una secuencia, pues pueden ser afectados por mas de una formula. La secuencia indicara que formula se aplciara en lugar de otra ya almacenada.


 	 *
 	 * @param id_moneda int Id de la moneda con la que se realizaran las operaciones.
 	 * @param nombre string Nombre de la tarifa.
 	 * @param tipo_tarifa string Puede ser "venta" o "compra" e indica si la tarifa sera aplicada en las operaciones de venta o compra.
 	 * @param default bool Si esta tarifa va a ser la default del sistema o no
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:
        "formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacionde este metodo. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula.
 	 * @return id_tarifa int Id de la tarifa creada
 	 **/
	public static function Nueva
	(
		$id_moneda, 
		$nombre, 
		$tipo_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null
	)
	{                         
            
            Logger::log("Creando nueva tarifa desde metodo publico");
            
            //Si se reciben formulas, se validan
            if(!is_null($formulas))
            {
                $formulas = object_to_array($formulas);
                if(!is_array($formulas))
                {
                    Logger::error("Las formulas son invalidas");
                    throw new Exception("Las formulas son invalidas",901);
                }
            }
            
            /*
             * Este metodo crea una tarifa, una version y una regla.
             * 
             * Si es obtenida una fecha de inicio o de fin, entonces se crea una version con esas fechas
             * y aparte se crea una version sin fechas que sera la default de la tarifa. Esta version default
             * tendra una unica regla que dejara los precios tal como son ( sin incrementos ni redondeos ni nada).
             * 
             * La version creada sera la activa en la tarifa. 
             * 
             * Las formulas obtenidas deberan contener los parametros de la tabla regla y por cada una se creara
             * una regla dentro de la version activa de la tarifa.
             */
            DAO::transBegin();
            try
            {
                
                //parametros de Nuevatarifa Base
                //$id_moneda, 
		//$nombre, 
		//$tipo_tarifa, 
		//$activa = null, 
		//$default = null
                
                //Se crea la tarifa base con los parametros obtenidos
                $id_tarifa = self::NuevaTarifaBase($id_moneda, $nombre, $tipo_tarifa, 1, $default);
                
                //Despues se crea la version de la tarifa. Si se recibe una fecha de inicio o de fin,
                //Se crea otra version que sera la default de la tazrifa con una regla sin cambios a los precios
                if( !is_null($fecha_inicio) || !is_null($fecha_fin) )
                {
                    
                    //parametros para nueva version
                    //$id_tarifa, 
                    //$nombre, 
                    //$activa = null, 
                    //$default = null, 
                    //$fecha_fin = null, 
                    //$fecha_inicio = null
                    
                    
                    $id_version_default = self::NuevaVersion($id_tarifa, $nombre." v1", null, 1);
                    $nombre_version = $nombre." v2";
                    $id_version = self::NuevaVersion($id_tarifa, $nombre_version, 1, NULL, $fecha_fin, $fecha_inicio);
                    
                    $id_regla_default = self::NuevaRegla($id_version_default, $nombre." v1 r1", 1, 1, null, null, NULL, null, null, -1, null, 0, 0, 0, 0, 0);
                }
                else
                {
                    //nueva version
                    //$id_tarifa, 
                    //$nombre, 
                    //$activa = null, 
                    //$default = null, 
                    //$fecha_fin = null, 
                    //$fecha_inicio = null
                    
                    $nombre_version = $nombre." v1";
                    $id_version = self::NuevaVersion($id_tarifa, $nombre_version, 1, 1, $fecha_fin, $fecha_inicio);
                }
                
                //Se validan las formlas recibidas y se crean las reglas para cada una
                $contador = 1;
                if(!is_null($formulas))
                {
                    foreach($formulas as $formula)
                    {
                        if
                        (!array_key_exists("secuencia", $formula) )
                        {
                            throw new Exception("La formula recibida no cuenta con el parametro secuencia",901);
                        }
                        self::NuevaRegla
                        (
                                $id_version, 
                                //$nombre_version." r".$contador, 
                                $formula["nombre"], 
                                $formula["secuencia"], 
                                array_key_exists("cantidad_minima", $formula) ? $formula["cantidad_minima"] : 1, 
                                array_key_exists("id_clasificacion_producto", $formula) ? $formula["id_clasificacion_producto"] : null, 
                                array_key_exists("id_clasificacion_servicio", $formula) ? $formula["id_clasificacion_servicio"] : null, 
                                array_key_exists("id_paquete", $formula) ? $formula["id_paquete"] : null, 
                                array_key_exists("id_producto", $formula) ? $formula["id_producto"] : null,
                                array_key_exists("id_servicio", $formula) ? $formula["id_servicio"] : null,
                                array_key_exists("id_tarifa", $formula) ? $formula["id_tarifa"] : null,
                                array_key_exists("id_unidad", $formula) ? $formula["id_unidad"] : null,
                                array_key_exists("margen_max", $formula) ? $formula["margen_max"] : 0,
                                array_key_exists("margen_min", $formula) ? $formula["margen_min"] : 0,
                                array_key_exists("metodo_redondeo", $formula) ? $formula["metodo_redondeo"] : 0,
                                array_key_exists("porcentaje_utilidad", $formula) ? $formula["porcentaje_utilidad"] : 0,
                                array_key_exists("utilidad_neta", $formula) ? $formula["utilidad_neta"] : 0
                        );
                        $contador++;
                    }
                }
                else
                {
                    $id_regla_default = self::NuevaRegla($id_version, $nombre_version." r1", 1, 1, null, null, NULL, null, null, -1, null, 0, 0, 0, 0, 0);
                }
                
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo crear la tarifa: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo crear la tarifa: ".$e->getMessage(),901);
                throw new Exception("No se pudo crear la tarifa, intentelo de nuevo mas tarde o contacte al administrador del sistema",901);
            }
            DAO::transEnd();

			Logger::log("Tarifa ".$id_tarifa." creada exitosamente" );
			
            return array("id_tarifa" => (int)$id_tarifa);
	}
  
	/**
 	 *
 	 *Edita la informacion de una tarifa. Este metodo puede cambiar las formulas de una tarifa o la vigencia de la misma. 

Este metodo tambien puede ponder como default esta tarifa o quitarle el default. Si se le quita el default, automaticamente se pone como default la predeterminada del sistema.
Si se obtienen formulas en este metodo, se borraran todas las formulas de esta tarifa y se aplicaran las recibidas

Si se cambia el tipo de tarifa, se verfica que esta tarifa no sea una default para algun rol, usuario, clasificacion de cliente o de proveedor, y pierde su default si fuera la default, poniendo como default la predetermianda del sistema.

Aplican todas las consideraciones de la documentacion del metodo nuevaTarifa
 	 *
 	 * @param id_tarifa int Id de la tarifa a editar
 	 * @param default bool Si esta tarifa sera la default
 	 * @param fecha_fin string Fecha a partir de la cual se dejaran de aplicar las formulas de esta tarifa
 	 * @param fecha_inicio string Fecha a partir de la cual se aplicaran las formulas de esta tarifa
 	 * @param formulas json Un arreglo de objetos que contendran la siguiente informacion:        
"formulas" : [
       {
          "id_producto"                 : null,
          "id_unidad"                   : null,
          "id_clasificacion_producto"   : null,
          "id_servicio"                 : null,
          "id_paquete"                  : null,
          "id_clasificacion_servicio"   : null,
          "cantidad_minima"             : null,
          "id_tarifa"                   : -1,
          "porcentaje_utilidad"         : 0.00,
          "utilidad_neta"               : 0.00,
          "metodo_redondeo"             : 0.00,
          "margen_min"                  : 0.00,
          "margen_max"                  : 0.00,
          "secuencia"                   : 5
                 }
       ]
   Para mas informacion de estos parametros consulte la documentacion del metodo nuevaTarifa. El parametro id_tarifa es la tarifa base de donde se sacara el Precio Base para la formula. La tarifa -1 inidica que no hay una tarifa base, sino que se toma el precio base del producto, o su costo base, segun marque su metodo de costeo.
 	 * @param id_moneda int Id de la moneda con la cual se realizaran todos los movimientos de la tarifa
 	 * @param nombre string Nombre de la tarifa
 	 * @param tipo_tarifa string Puede ser "compra" o "venta" e indica si la tarifa sera de compra o de venta
 	 **/
	public static function Editar
	(
		$id_tarifa, 
		$default = null, 
		$fecha_fin = null, 
		$fecha_inicio = null, 
		$formulas = null, 
		$id_moneda = null, 
		$nombre = null, 
		$tipo_tarifa = null
	)
	{  
            Logger::log("Editando la tarifa ".$id_tarifa." desde metodo publico");
            
            //Se llama a los metodos internos, pues ellos hacen las validaciones
            DAO::transBegin();
            try
            {
                self::EditarTarifaBase($id_tarifa, $id_moneda, $nombre, $tipo_tarifa);
                
                $tarifa = TarifaDAO::getByPK($id_tarifa);
                
                if($default && !$tarifa->getDefault())
                {
                    switch($tarifa->getTipoTarifa())
                    {
                        case "compra": self::CompraSetDefaultTarifa($id_tarifa);
                            break;
                        case "venta" :self::VentaSetDefaultTarifa($id_tarifa);
                            break;
                    }
                }
                
                //Si la version activa de la tarifa es la version default con reglas puestas por el usuario,
                //y al editarse se le quiere poner fecha de inicio o de fin, entonces se tiene que crear
                //otra version default que no tenga caducidad y con una regla que no haga ningun cambio a los precios
                if
                (
                        $tarifa->getIdVersionActiva() == $tarifa->getIdVersionDefault() &&
                        (!is_null($fecha_inicio) || !is_null($fecha_fin))
                )
                {
                    $id_version_default = self::NuevaVersion($id_tarifa, $tarifa->getNombre()." vd", 0, 1);
                    $id_regla_default = self::NuevaRegla($id_version_default, $tarifa->getNombre()." vd rd", 1, 1, null, null, null, null, null, -1, null, 0, 0, 0, 0, 0);
                    $tarifa->setIdVersionDefault($id_version_default);
                    TarifaDAO::save($tarifa);
                }
                
                self::EditarVersion($tarifa->getIdVersionActiva(), $fecha_fin, $fecha_inicio);
                
                //Si se reciben formulas, se eliminan todas las formlas en la version activa y se
                //insertan las formulas recibidas.
                if(!is_null($formulas))
                {
                    $id_version = $tarifa->getIdVersionActiva();
                    $nombre_version = VersionDAO::getByPK($id_version)->getNombre();
                    $contador = 0;
                    
                    $reglas = ReglaDAO::search( new Regla( array( "id_version" => $id_version ) ) );
                    foreach($reglas as $regla)
                    {
                        self::EliminarRegla($regla->getIdRegla());
                    }
                    
                    foreach($formulas as $formula)
                    {
                        if
                        (!array_key_exists("secuencia", $formula) )
                        {
                            throw new Exception("La formula recibida no cuenta con el parametro secuencia",901);
                        }
                        self::NuevaRegla
                        (
                                $id_version, 
                                $nombre_version." r".$contador, 
                                $formula["secuencia"], 
                                array_key_exists("cantidad_minima", $formula) ? $formula["cantidad_minima"] : 1, 
                                array_key_exists("id_clasificacion_producto", $formula) ? $formula["id_clasificacion_producto"] : null, 
                                array_key_exists("id_clasificacion_servicio", $formula) ? $formula["id_clasificacion_servicio"] : null, 
                                array_key_exists("id_paquete", $formula) ? $formula["id_paquete"] : null, 
                                array_key_exists("id_producto", $formula) ? $formula["id_producto"] : null,
                                array_key_exists("id_servicio", $formula) ? $formula["id_servicio"] : null,
                                array_key_exists("id_tarifa", $formula) ? $formula["id_tarifa"] : null,
                                array_key_exists("id_unidad", $formula) ? $formula["id_unidad"] : null,
                                array_key_exists("margen_max", $formula) ? $formula["margen_max"] : 0,
                                array_key_exists("margen_min", $formula) ? $formula["margen_min"] : 0,
                                array_key_exists("metodo_redondeo", $formula) ? $formula["metodo_redondeo"] : 0,
                                array_key_exists("porcentaje_utilidad", $formula) ? $formula["porcentaje_utilidad"] : 0,
                                array_key_exists("utilidad_neta", $formula) ? $formula["utilidad_neta"] : 0
                        );
                        $contador++;
                    }
                }
                
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la tarifa: ".$e);
                if($e->getCode()==901)
                    throw new Exception("No se pudo editar la tarifa: ".$e->getMessage(),901);
                throw new Exception("No se pudo editar la tarifa, intentelo de nuevo mas tarde o consulte a su administrador de sistema",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa editada exitosamente");
	}
        
        /**
 	 *
 	 *Desactiva una tarifa. Para poder desactivar una tarifa, esta no tiene que estar asignada como default para ningun usuario. La tarifa default del sistema no puede ser eliminada.

La tarifa instalada por default no puede ser eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a eliminar
 	 **/
	public static function Desactivar
	(
		$id_tarifa
	)
	{  
            Logger::log("Desactivando la tarifa ".$id_tarifa);
            
            //Se valida la tarifa recibida
            $validar = self::ValidarParametrosTarifa($id_tarifa);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            $tarifa = TarifaDAO::getByPK($id_tarifa);
            
            if($tarifa->getDefault())
            {
                Logger::error("Se quiere eliminar una tarifa por default");
                throw new Exception("Se quiere eliminar una tarifa por default, primero cambie la tarifa default y despues vuelva a intentarlo",901);
            }
            
            $tarifa->setActiva(0);
            
            DAO::transBegin();
            try
            {
                TarifaDAO::save($tarifa);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo eliminar la tarifa: ".$e);
                throw new Exception("No se pudo eliminar la tarifa, intentelo de nuevo mas tarde o consulte al administrado del sistema",901);
            }
            DAO::transEnd();
            Logger::log("Tarifa desactivada exitosamente");
  
	}
        
        /**
 	 *
 	 *Activa una tarifa preciamente eliminada
 	 *
 	 * @param id_tarifa int Id de la tarifa a activar
 	 **/
	public static function Activar
	(
		$id_tarifa
	)
	{
            
        }

  }
