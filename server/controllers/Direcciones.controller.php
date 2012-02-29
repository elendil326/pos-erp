<?php




/**
  *
  *	
  * @todo validarDireccion debe depender de nivel de exigencia en los datos
  *
  **/        
class DireccionController extends ValidacionesController{
	


	public static function validarDireccion( Direccion $direccion ){
		
			return true;
			
            if(!is_null($calle)){
                if(self::validarLongitudDeCadena($calle, 1, 128 )){
					return false;
				}
            }

            if(!is_null($numero_exterior)){
                if(self::validarLongitudDeCadena($numero_exterior, 1, 128 )){
					return false;
				}
            }


            if(!is_null($numero_exterior)){
				//preg_match('/[^a-zA-Z0-9\(\)\- ]/',$numero_exterior)
                if(self::validarLongitudDeCadena($numero_exterior, 1, 128 )){
					return false;
				}
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
            Logger::log("Insertando nueva direccion ...");



            $direccion = new Direccion();

            $id_usuario =  SesionController::getCurrentUser(   );

            if($id_usuario == null){	
				Logger::error("SesionController::getCurrentUser() regreso null");
                throw new Exception("No se pudo obtener la sesion del usuario, ya inicio sesion?",901);
            }

            if(!self::validarDireccion($direccion)){
				throw new InvalidDataException();
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
            try{
                DireccionDAO::save($direccion);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                throw new Exception("No se pudo crear la direccion: ".$e);
            }

            DAO::transEnd();

            Logger::log("Direccion creada exitosamente, id=" . $direccion->getIdDireccion() );
            
            return $direccion->getIdDireccion();
        }
}