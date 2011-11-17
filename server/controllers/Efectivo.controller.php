<?php
require_once("interfaces/Efectivo.interface.php");
/**
  *
  *
  *
  **/
	
  class EfectivoController implements IEfectivo{
  
      
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
         * Valida los parametros de la tabla billete. Regresa un string con el error en caso de encontrarse alguno.
         * De lo contrario, regresa verdadero
         */
        private static function validarParametrosBillete
        (
                $id_billete = null,
                $id_moneda = null,
                $nombre = null,
                $valor = null,
                $foto_billete = null,
                $activo = null
        )
        {
            //valida que el billete exista y que este activo
            if(!is_null($id_billete))
            {
                $billete = BilleteDAO::getByPK($id_billete);
                if(is_null($billete))
                    return "El billete ".$id_billete." no existe";
                
                if(!$billete->getActivo())
                    return "El billete ".$id_billete." no esta activo";
            }
            
            //valida que la moneda exista y que sea activa
            if(!is_null($id_moneda))
            {
                $e = self::validarParametrosMoneda($id_moneda);
                if(is_string($e))
                    return $e;
            }
            
            //valida que el nombre este en rango y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 50, "nombre");
                if(is_string($e))
                    return $e;
                
                $billetes = BilleteDAO::search( new Billete( array( "nombre" => trim($nombre) ) ) );
                foreach($billetes as $billete)
                {
                    if($billete->getActivo())
                        return "El nombre (".$nombre.") ya esta siendo usado por el billete ".$billete->getIdBillete();
                }
            }
            
            //valida que el valor este en rango
            if(!is_null($valor))
            {
                $e = self::validarNumero($valor, 1.8e200, "valor");
                if(is_string($e))
                    return $e;
            }
            
            //valida que la foto del billete este en rango
            if(!is_null($foto_billete))
            {
                $e = self::validarString($foto_billete, 100, "foto de billete");
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
            
            //no se encontro error
            return true;
        }
        
        /*
         * Valida los parametros de la tabla moneda. Regresa un string con el error en caso de encontrarse alguno.
         * De lo contrario, regresa verdadero
         */
        private static function validarParametrosMoneda
        (
                $id_moneda = null,
                $nombre = null,
                $simbolo = null,
                $activa = null
        )
        {
            //valida que la moneda exista y que este activa
            if(!is_null($id_moneda))
            {
                $moneda = MonedaDAO::getByPK($id_moneda);
                if(is_null($moneda))
                    return "La moneda ".$id_moneda." no existe";
                
                if(!$moneda->getActiva())
                    return "La moneda ".$id_moneda." no esta activa";
            }
            
            //valida que el nombre sea valido y que no se repita
            if(!is_null($nombre))
            {
                $e = self::validarString($nombre, 100, "nombre");
                if(is_string($e))
                    return $e;
                
                $monedas = MOnedaDAO::search( new Moneda( array( "nombre" => trim($nombre) ) ) );
                foreach($monedas as $moneda)
                {
                    if($moneda->getActiva())
                        return "El nombre (".$nombre.") ya esta en uso por la moneda ".$moneda->getIdMoneda();
                }
            }
            
            //valida que el simbolo este en rango
            if(!is_null($simbolo))
            {
                $e = self::validarString($simbolo, 10, "simbolo");
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
            
            //no se encontro error
            return true;
        }
      
      
      
      
      
      
      
  
	/**
 	 *
 	 *Crea un nuevo billete, se puede utilizar para monedas tambien.
 	 *
 	 * @param nombre string Nombre del billete, puede ser el valor en texto, "cincuenta", "cien", etc.
 	 * @param valor int Valor del billete
 	 * @param id_moneda int Id de la moneda a la que pertence el billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @return id_billete int Id del billete autogenerado
 	 **/
	public static function NuevoBillete
	(
		$nombre, 
		$valor, 
		$id_moneda, 
		$foto_billete = null
	)
	{  
            Logger::log("Creando nuevo billete");
            
            //se validan los parametros recibidos
            $validar = self::validarParametrosBillete(null, $id_moneda, $nombre, $valor, $foto_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se crea el nuevo billete y se guarda
            $billete = new Billete(
                    array(
                        "nombre"        => $nombre,
                        "valor"         => $valor,
                        "id_moneda"     => $id_moneda,
                        "foto_billete"  => $foto_billete,
                        "activo"        => 1
                    )
                    );
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se ha podido crear el nuevo billete: ".$e);
                throw new Exception("No se ha podido crear el nuevo billete");
            }
            DAO::transEnd();
            Logger::log("Billete creado exitosamente");
            return array( "id_billete" => $billete->getIdBillete() );
	}
  
	/**
 	 *
 	 *Edita la informacion de un billete
 	 *
 	 * @param id_billete int Id del billete a editar
 	 * @param valor int Valor del billete
 	 * @param foto_billete string Url de la foto del billete
 	 * @param nombre string Nombre del billete, valor en texto, "cincuenta", "cien", etc
 	 * @param id_moneda int Id de la moneda a la que pertenece el billete
 	 **/
	public static function EditarBillete
	(
		$id_billete, 
		$valor = null, 
		$foto_billete = null, 
		$nombre = null, 
		$id_moneda = null
	)
	{  
            Logger::log("Editando billete ".$id_billete);
            
            //se validan los parametros recibidos
            $validar = self::validarParametrosBillete($id_billete,$id_moneda,$nombre,$valor,$foto_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Los parametros que no sean nulos seran tomados como actualizacion
            $billete = BilleteDAO::getByPK($id_billete);
            if(!is_null($valor))
            {
                $billete->setValor($valor);
            }
            if(!is_null($foto_billete))
            {
                $billete->setFotoBillete($foto_billete);
            }
            if(!is_null($nombre))
            {
                $billete->setNombre($nombre);
            }
            if(!is_null($id_moneda))
            {
                $billete->setIdMoneda($id_moneda);
            }
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo editar el billete: ".$e);
                throw new Exception("No se pudo editar el billete");
            }
            DAO::transEnd();
            Logger::log("Billete editado exitosamente");
	}
  
	/**
 	 *
 	 *Desactiva un billete
 	 *
 	 * @param id_billete int Id del billete a desactivar
 	 **/
	public static function EliminarBillete
	(
		$id_billete
	)
	{  
            Logger::log("Eliminando el billete ".$id_billete);
            
            //Se valida que el billete exista y este activo
            $validar = self::validarParametrosBillete($id_billete);
            if(is_string($validar))
            {
                Logger::error($validar);
                throw new Exception($validar);
            }
            
            //Se desactiva el billete y se guarda
            $billete = BilleteDAO::getByPK($id_billete);
            $billete->setActivo(0);
            DAO::transBegin();
            try
            {
                BilleteDAO::save($billete);
            }
            catch(Exception $e)
            {
                DAO::transEnd();
                Logger::error("No se pudo eliminar el billete: ".$e);
                throw new Exception("No se pudo eliminar el billete");
            }
            DAO::transEnd();
            Logger::log("El billete ha sido eliminado exitosamente");
            
	}
  
	/**
 	 *
 	 *Lista los billetes de una instancia
 	 *
 	 * @param ordenar json Valor que determina el orden de la lista
 	 * @param activo bool Si este valor no es obtenido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos
 	 * @return billetes json Lista de billetes
 	 **/
	public static function ListaBillete
	(
		$ordenar = "", 
		$activo = ""
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Crea una moneda, "pesos", "dolares", etc.
 	 *
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 * @return id_moneda int Id de la moneda recien creada
 	 **/
	public static function NuevaMoneda
	(
		$nombre, 
		$simbolo
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Edita la informacion de una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a editar
 	 * @param nombre string Nombre de la moneda
 	 * @param simbolo string Simbolo de la moneda
 	 **/
	public static function EditarMoneda
	(
		$id_moneda, 
		$nombre = "", 
		$simbolo = ""
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Desactiva una moneda
 	 *
 	 * @param id_moneda int Id de la moneda a desactivar
 	 **/
	public static function EliminarMoneda
	(
		$id_moneda
	)
	{  
  
  
	}
  
	/**
 	 *
 	 *Lista las monedas de una instancia
 	 *
 	 * @param orden json Valor que determinara el orden de la lista
 	 * @param activo bool Si este valor no es recibido, se listaran tanto activos como inactivos, si es verdadero, se listaran solo los activos, si es falso, se listaran solo los inactivos.
 	 * @return monedas json Lista de monedas
 	 **/
	public static function ListaMoneda
	(
		$orden = "", 
		$activo = ""
	)
	{  
  
  
	}
  }
