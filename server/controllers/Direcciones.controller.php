<?php




/**
  *
  *	
  * @todo validarDireccion debe depender de nivel de exigencia en los datos
  *
  **/        
class DireccionController{
	
        private static function validarString($string, $max_length, $nombre_variable,$min_length=0)
	{
		if(strlen($string)<=$min_length||strlen($string)>$max_length)
		{
		    return "La longitud de la variable ".$nombre_variable." proporcionada (".$string.") no esta en el rango de ".$min_length." - ".$max_length;
		}
		return true;
    }



	private static function validarNumero($num, $max_length, $nombre_variable, $min_length=0)
	{
	    if($num<$min_length||$num>$max_length)
	    {
	        return "La variable ".$nombre_variable." proporcionada (".$num.") no esta en el rango de ".$min_length." - ".$max_length;
	    }
	    return true;
	}

	public static function validarParametrosDireccion
        (
                $id_direccion = null,
                $calle = null,
                $numero_exterior = null,
                $numero_interior = null,
                $referencia = null,
                $colonia = null,
                $id_ciudad = null,
                $codigo_postal = null,
                $telefono = null,
                $telefono2 = null
        )
        {
            if(!is_null($id_direccion))
            {
                if(is_null(DireccionDAO::getByPK($id_direccion)))
                {
                    return "La direccion con id: ".$id_direccion." no existe";
                }
            }
            if(!is_null($calle))
            {
                $e=self::validarString($calle, 128, "calle");
                if(is_string($e))
                    return $e;
            }
            if(!is_null($numero_exterior))
            {
                $e=self::validarString($numero_exterior, 8, "numero exterior");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^a-zA-Z0-9\(\)\- ]/',$numero_exterior))
                        return "El numero exterior ".$numero_exterior." tiene caracteres fuera del rango a-z,A-Z,0-9,(,),- y espacio vacio";
            }
            if(!is_null($numero_interior))
            {
                $e=self::validarString($numero_interior, 8, "numero interior");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^a-zA-Z0-9\(\)\- ]/',$numero_interior))
                        return "El numero interior ".$numero_interior." tiene caracteres fuera del rango a-z,A-Z,0-9,(,),-, y espacio vacio";
            }
            if(!is_null($referencia))
            {
                $e=self::validarString($referencia, 256, "referencia");
                if(is_string($e))
                    return $e;
            }
            if(!is_null($colonia))
            {
                $e=self::validarString($colonia, 128, "colonia");
                if(is_string($e))
                    return $e;
            }
            if(!is_null($id_ciudad))
            {
                if(is_null(CiudadDAO::getByPK($id_ciudad)))
                    return "La ciudad con id: ".$id_ciudad." no existe";
            }
            if(!is_null($codigo_postal))
            {
                $e=self::validarString($codigo_postal, 10, "codigo postal");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^0-9]/',$codigo_postal))
                        return "El codigo postal ".$codigo_postal." tiene caracteres fuera del rango 0-9";
            }
            if(!is_null($telefono))
            {
                $e=self::validarString($telefono, 32, "telefono");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^0-9\- \(\)\*]/',$telefono))
                  return "El telefono ".$telefono." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
            }
            if(!is_null($telefono2))
            {
                $e=self::validarString($telefono2, 32, "telefono alterno");
                if(is_string($e))
                    return $e;
                if(preg_match('/[^0-9\- \(\)\*]/',$telefono2))
                  return "El telefono alterno ".$telefono2." tiene caracteres fuera del rango 0-9,-,(,),* o espacio vacío";
            }
        }
	

        public static function NuevaDireccion(
                $calle,
                $numero_exterior,
                $colonia,
                $id_ciudad,
                $codigo_postal,
                $numero_interior=null,
                $referencia=null,
                $telefono=null,
                $telefono2=null
        )
        {
            Logger::log("Creando nueva direccion");

            $validar = self::validarParametrosDireccion(null, $calle, $numero_exterior, $numero_interior, $referencia,
                    $colonia, $id_ciudad, $codigo_postal, $telefono, $telefono2);

            if(is_string($validar))
            {
                throw new Exception($validar);
            }

            $direccion = new Direccion();

            $id_usuario=  SesionController::getCurrentUser();

            if($id_usuario==null)
            {
                throw new Exception("No se pudo obtener la sesion del usuario, ya inicio sesion?");
            }

            if(!is_null($telefono)&&$telefono==$telefono2)
            {
                throw new Exception("El telefono ".$telefono." es igual al telefono alterno ".$telefono2);
            }
            $direccion->setCalle($calle);
            $direccion->setNumeroExterior($numero_exterior);
            $direccion->setColonia($colonia);
            $direccion->setIdCiudad($id_ciudad);
            $direccion->setCodigoPostal($codigo_postal);
            $direccion->setNumeroInterior($numero_interior);
            $direccion->setReferencia($referencia);
            $direccion->setTelefono($telefono);
            $direccion->setTelefono2($telefono2);
            $direccion->setUltimaModificacion( date( "Y-m-d H:i:s" , time() ));
            $direccion->setIdUsuarioUltimaModificacion($id_usuario);
            DAO::transBegin();
            try
            {
                DireccionDAO::save($direccion);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                throw new Exception("No se pudo crear la direccion: ".$e);
            }

            DAO::transEnd();

            Logger::log("Direccion creada exitosamente");
            
            return $direccion->getIdDireccion();
        }
}