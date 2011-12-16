<?php
require_once("interfaces/ImpuestosYRetenciones.interface.php");
/**
  *
  *
  *
  **/
	
  class ImpuestosYRetencionesController implements IImpuestosYRetenciones{
  
      
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
         * Valida el boleano esMonto
         */
        private static function validarEsMonto
        (
                $es_monto
        )
        {
            $e = self::validarNumero($es_monto, 1, "es_monto");
            if(is_string($e))
                return $e;
            return true;
        }
        
        /*
         * Valida el parametro monto_procentaje
         */
        private static function validarMontoPorcentaje
        (
                $monto_porcentaje
        )
        {
            $e = self::validarNumero($monto_porcentaje, 1.8e200, "monto o porcentaje");
            if(is_string($e))
                return $e;
            return true;
        }
        
        /*
         * Valida la variable nombre
         */
        private static function validarNombre
        (
                $nombre
        )
        {
            $e = self::validarString($nombre, 100, "nombre");
            if(is_string($e))
                return $e;
            return true;
        }
        
        /*
         * Valida la variable descricpion
         */
        private static function validarDescripcion
        (
                $descripcion
        )
        {
            $e = self::validarString($descripcion, 255, "descripcion");
            if(is_string($e))
                return $e;
            return true;
        }
        
        /*
         * Valida el parametro ordenar
         */
        private static function validarOrdenar
        (
                $ordenar
        )
        {
            if
            (
                    $ordenar != "monto_porcentaje"  &&
                    $ordenar != "es_monto"          &&
                    $ordenar != "nombre"            &&
                    $ordenar != "descripcion"
            )
                return "La variable ordenar(".$ordenar.") es invalida";
            return true;
        }
        
        
        
        
        
        
  
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
		$es_monto = null, 
		$monto_porcentaje = null, 
		$descripcion = null, 
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
            try
            {
                ImpuestoDAO::save($impuesto);
            }
            catch(Exception $e)
            {
                Logger::error($e);
                throw new Exception("No se pudo editar el impuesto, consulte al administrador del sistema");
            }
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
		$es_monto = "", 
		$monto_porcentaje = "", 
		$descripcion = "", 
		$nombre = ""
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
            try
            {
                RetencionDAO::save($retencion);
            }
            catch(Exception $e)
            {
                Logger::error($e);
                throw new Exception("No se pudo editar la retencion, consulte al administrador del sistema");
            }
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
		$monto_porcentaje, 
		$nombre, 
		$es_monto, 
		$descripcion = ""
	)
	{  
  
  
	}
  }
