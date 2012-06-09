<?php
require_once("interfaces/ImpuestosYRetenciones.interface.php");
/**
  *
  *
  *
  **/
	
  class ImpuestosYRetencionesController implements IImpuestosYRetenciones{

	/**
 	 *
 	 *Edita la informacion de un impuesto
 	 *
 	 * @param id_impuesto int Id del impuesto a editar
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje que representa este impuesto
 	 * @param descripcion string Descripcion larga del impuesto
 	 * @param nombre string Nombre del impuesto
 	 **/
	public static function EditarImpuesto
	(
		$id_impuesto, 
		$descripcion = null, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$nombre = null
	)
	{  
            Logger::log("Editando impuesto ".$id_impuesto);
            $impuesto = ImpuestoDAO::getByPK($id_impuesto);
            
            //Se valida que el impuesto a editar exista
            if(is_null($impuesto))
            {
                Logger::error("El impuesto ".$id_impuesto." no existe");
                throw new Exception("El impuesto ".$id_impuesto." no existe");
            }
            
            //Se validan y actualizan solo los parametros que son recibidos.
            if(!is_null($es_monto))
            {
                $e = self::validarEsMonto($es_monto);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $impuesto->setEsMonto($es_monto);
            }
            if(!is_null($monto_porcentaje))
            {
                $e = self::validarMontoPorcentaje($monto_porcentaje);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $impuesto->setMontoPorcentaje($monto_porcentaje);
            }
            if(!is_null($descripcion))
            {
                $e = self::validarDescripcion($descripcion);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $impuesto->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                $e = self::validarNombre($nombre);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $impuesto->setNombre($nombre);
            }
            DAO::transBegin();
            try
            {
                ImpuestoDAO::save($impuesto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el impuesto: ".$e);
                throw new Exception("No se pudo editar el impuesto, consulte al administrador del sistema");
            }
            DAO::transEnd();
            Logger::log("Impuesto editado exitosamente");
	}
  
	/**
 	 *
 	 *Edita la informacion de una retencion
 	 *
 	 * @param id_retencion int Id de la retencion a editar
 	 * @param es_monto bool Si es verdadero, el campo monto_porcentaje sera tomado como un monto fijo, si es false, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o porcentaje de la retencion
 	 * @param descripcion string Descripcion larga de al retencion
 	 * @param nombre string Nombre de la retencion
 	 **/
	public static function EditarRetencion
	(
		$id_retencion, 
		$descripcion = null, 
		$es_monto = null, 
		$monto_porcentaje = null, 
		$nombre = null
	)
	{  
            Logger::log("Editando retencion ".$id_retencion);
            $retencion = RetencionDAO::getByPK($id_retencion);
            
            //Se valida que el impuesto a editar exista
            if(is_null($retencion))
            {
                Logger::error("La retencion ".$id_retencion." no existe");
                throw new Exception("La retencion ".$id_retencion." no existe");
            }
            
            //Se validan y actualizan solo los parametros que son recibidos.
            if(!is_null($es_monto))
            {
                $e = self::validarEsMonto($es_monto);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $retencion->setEsMonto($es_monto);
            }
            if(!is_null($monto_porcentaje))
            {
                $e = self::validarMontoPorcentaje($monto_porcentaje);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $retencion->setMontoPorcentaje($monto_porcentaje);
            }
            if(!is_null($descripcion))
            {
                $e = self::validarDescripcion($descripcion);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $retencion->setDescripcion($descripcion);
            }
            if(!is_null($nombre))
            {
                $e = self::validarNombre($nombre);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
                $retencion->setNombre($nombre);
            }
            DAO::transBegin();
            try
            {
                RetencionDAO::save($retencion);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar la retencion: ".$e);
                throw new Exception("No se pudo editar la retencion, consulte al administrador del sistema");
            }
            DAO::transEnd();
            Logger::log("Retencion editada exitosamente");
	}
  
	/**
 	 *
 	 *Lista las retenciones
 	 *
 	 * @param ordenar json Objeto que determinara el orde de la lista
 	 * @return retenciones json Objeto que contendra la lista de retenciones
 	 **/
	public static function ListaRetencion
	(
		$ordenar = null
	)
	{  
            Logger::log("Listando todas las retenciones");
            
            //Si se recibio el parametro ordenar, se valida
            if($ordenar!=null)
            {
                $e = self::validarOrdenar($ordenar);
                if(is_string($e))
                {
                    if($ordenar!="id_retencion")
                    {
                        Logger::error($e);
                        throw new Exception($e);
                    }
                }
            }
            return RetencionDAO::getAll(null, null, $ordenar);
	}
  
	/**
 	 *
 	 *Listas los impuestos
 	 *
 	 * @param ordenar json Objeto que determinara el orden de la lista
 	 * @return impuestos json Lista de impuestos
 	 **/
	public static function ListaImpuesto
	(
		$ordenar = null
	)
	{
            Logger::log("Listando todos los impuestos");
            
            //Si se recibio el parametro ordenar, se valida
            if($ordenar!=null)
            {
                $e = self::validarOrdenar($ordenar);
                if(is_string($e))
                {
                    if($ordenar!="id_impuesto")
                    {
                        Logger::error($e);
                        throw new Exception($e);
                    }
                }
            }
            return ImpuestoDAO::getAll(null, null, $ordenar);
	}
  
	/**
 	 *
 	 *Crea una nueva retencion
 	 *
 	 * @param es_monto float Si es veradera, el campo monto_porcentaje sera tomado como un monto fijo, si es falso, sera tomado como un porcentaje
 	 * @param monto_porcentaje float Monto o procentaje que representa esta retencion
 	 * @param nombre string Nombre de la retencion
 	 * @param descripcion string Descripcion larga de la retencion
 	 * @return id_retencion int Id de la retencion creada
 	 **/
	public static function NuevaRetencion
	(
		$es_monto, 
		$monto_porcentaje, 
		$nombre, 
		$descripcion = null
	)
	{  
            Logger::log("Creando una nueva retencion");
            
            //Se validan los parametros recibidos
            $e = self::validarEsMonto($es_monto);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            $e = self::validarMontoPorcentaje($monto_porcentaje);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            $e = self::validarNombre($nombre);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            if(!is_null($descripcion))
            {
                $e = self::validarDescripcion($descripcion);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
            }
            
            $retencion = new Retencion(
                    array(
                        "es_monto"          => $es_monto,
                        "monto_porcentaje"  => $monto_porcentaje,
                        "nombre"            => $nombre,
                        "descripcion"       => $descripcion
                    )
                    );
            DAO::transBegin();
            try
            {
                RetencionDAO::save($retencion);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear la nueva retencion: ".$e);
                throw new Exception("No se ha podido crear la nueva retencion, consulte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Retencion creada exitosamente");
            return array( "id_retencion" => $retencion->getIdRetencion() );
	}
  
	/**
 	 *
 	 *Crear un nuevo impuesto.
 	 *
 	 * @param monto_porcentaje float monto o porcentaje que representa este impuesto
 	 * @param nombre string Nombre del impuesto
 	 * @param es_monto bool Si es verdadero, el campo de monto_porcentaje sera tomado como un monto fijo, si es falso, sera tomado como un porcentaje
 	 * @param descripcion string Descripcion del impuesto
 	 * @return id_impuesto int Id del impuesto insertado.
 	 **/
	public static function NuevoImpuesto
	(
		$es_monto, 
		$monto_porcentaje, 
		$nombre, 
		$descripcion = null
	)
	{  
            Logger::log("Creando un nuevo impuesto");
            
            //Se validan los parametros recibidos
            $e = self::validarEsMonto($es_monto);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            $e = self::validarMontoPorcentaje($monto_porcentaje);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            $e = self::validarNombre($nombre);
            if(is_string($e))
            {
                Logger::error($e);
                throw new Exception($e);
            }
            if(!is_null($descripcion))
            {
                $e = self::validarDescripcion($descripcion);
                if(is_string($e))
                {
                    Logger::error($e);
                    throw new Exception($e);
                }
            }
            
            $impuesto = new Impuesto(
                    array(
                        "es_monto"          => $es_monto,
                        "monto_porcentaje"  => $monto_porcentaje,
                        "nombre"            => $nombre,
                        "descripcion"       => $descripcion
                    )
                    );
            DAO::transBegin();
            try
            {
                ImpuestoDAO::save($impuesto);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear el nuevo impuesto: ".$e);
                throw new Exception("No se ha podido crear al nuevo impuesto, consulte a su administrador de sistema");
            }
            DAO::transEnd();
            Logger::log("Impuesto creado exitosamente");
            return array( "id_impuesto" => $impuesto->getIdImpuesto() );
	}
  }
