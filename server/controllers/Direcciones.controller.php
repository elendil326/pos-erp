<?php




/**
  *
  *	
  * @todo validarDireccion debe depender de nivel de exigencia en los datos
  *
  **/        
class DireccionController{
	
    

	/**
	  *
	  * @param $direccion_vo Direccion
	  * 
	  **/
	public static function validarDireccion( $direccion_vo )
	{

		if( ( $direccion_vo instanceof Direccion ) === false )	
		{
			throw new InvalidArgumentException("You must suply a valid VO object to validadDireccion() ");
		}


		//validar calle
		if( is_null( $direccion_vo->getCalle() ) )
		{
			return false;
		}

		//la calle es muy corta
		if( strlen( $direccion_vo->getCalle() )  < 5 )
		{
			return false;
		}

		//validar numero exterior
		if( is_null( $direccion_vo->getNumeroExterior() ))
		{
			return false;
		}

		//validar numero interior


		//varlidar referencia


		//validar colonia


		//validar id_ciudad
		if( is_null( $direccion_vo->getIdCiudad() ))
		{
			return null;
		}

		//validar que exista esa ciudad
		if( is_null( CiudadDAO::getByPK( $direccion_vo->getIdCiudad() ) ) )
		{
			return null;
		}


		//validar codigo_postal


		//validar telefono

		return true;
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
            $direccion = new Direccion();
            if(CiudadDAO::getByPK($id_ciudad)==null)
            {
                Logger::error("La ciudad con id: ".$id_ciudad." no existe");
                throw new Exception("La ciudad con id: ".$id_ciudad." no existe");
            }
            $id_usuario=LoginController::getCurrentUser();
            if($id_usuario==null)
            {
                Logger::error("No se pudo obtener la sesion del usuario, ya inicio sesion?");
                throw new Exception("No se pudo obtener la sesion del usuario, ya inicio sesion?");
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
            $direccion->setUltimaModificacion( time() );
            $direccion->setIdUsuarioUltimaModificacion($id_usuario);
            DAO::transBegin();
            try
            {
                DireccionDAO::save($direccion);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                Logger::error("No se pudo guardar la direccion: ".$e);
                throw $e;
            }

            DAO::transEnd();

            Logger::log("Direccion creada exitosamente");

            return $direccion->getIdDireccion();
        }
}