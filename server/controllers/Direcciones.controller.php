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

            $id_usuario =  SesionController::Actual(   );
			$id_usuario = $id_usuario["id_usuario"];
			
			
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
	
		public static function EditarDireccion($direccion)
		{
		//verificamos si se cambiaron las direcciones
            if( !is_null($direccion) )
            {
            	Logger::log("	Editando direccion ...");

 
                if( !is_array( $direccion ) ){
                    //Logger::error("Verifique el formato de los datos de las direcciones, se esperaba un array ");
                    //throw new Exception("Verifique el formato de los datos de las empresas, se esperaba un array ");
					$direccion = object_to_array($direccion);
                }
                    $_direccion = new Direccion($direccion);
					
                    $d = DireccionDAO::getByPK( $_direccion->getIdDireccion() ); 


                    //verificamos si se va a editar una direccion o se va a crear una nueva
                    if( isset($d->id_direccion) ){

                        //se edita la direccion
                        if( !$_direccion = DireccionDAO::getByPK( $d->id_direccion ) ){
                            DAO::transRollback();
                            Logger::error("No se tiene registro de la dirección con id : {$direccion->id_direccion}");
                            throw new InvalidDataException("No se tiene registro de la dirección con id : {$direccion->id_direccion}");
                        }
                            
                        //bandera que indica si cambia algun parametro de la direccion
                        $cambio_direccion = false;
                            
                        //calle
                        if( isset( $d->calle ) ){
                            $cambio_direccion = true;
                            $_direccion->setCalle( $direccion['calle'] );
                        }
                            
                        //numero_exterior
                        if( isset( $d->numero_exterior ) ){
                            $cambio_direccion = true;
                            $_direccion->setNumeroExterior( $direccion['numero_exterior'] );
                        }
                            
                        //numero_interior
                        if( isset( $d->numero_interior ) ){
                            $cambio_direccion = true;
                            $_direccion->setNumeroInterior( $direccion['numero_interior'] );
                        }
                            
                        //referencia
                        if( isset( $d->referencia ) ){
                            $cambio_direccion = true;
                            $_direccion->setReferencia( $direccion['referencia'] );
                        }
                            
                        //colonia
                        if( isset( $d->colonia ) ){
                            $cambio_direccion = true;
                            $_direccion->setColonia( $direccion['colonia'] );
                        }
                            
                        //id_ciudad
                        if( isset( $d->id_ciudad ) ){
                            $cambio_direccion = true;
                            $_direccion->setIdCiudad( $direccion['id_ciudad'] );
                        }
                            
                        //codigo_postal
                        if( isset( $d->codigo_postal ) ){
                            $cambio_direccion = true;
                            $_direccion->setCodigoPostal( $direccion['codigo_postal'] );
                        }
                            
                        //telefono
                        if( isset( $d->telefono ) ){
                            $cambio_direccion = true;
                            $_direccion->setTelefono( $direccion['telefono'] );
                        }
                            
                        //telefono2
                        if( isset( $d->telefono2 ) ){
                            $cambio_direccion = true;
                            $_direccion->setTelefono2( $direccion['telefono2'] );
                        }

                        //Si cambio algun parametro de direccion, se actualiza el usuario que modifica y la fecha
                        if($cambio_direccion)
                        {
                             
                            $_direccion->setUltimaModificacion( time() );

                            
                            $id_usuario =  SesionController::Actual(   );

							$id_usuario = $id_usuario["id_usuario"];
							
                            if(is_null($id_usuario))
                            {
                                DAO::transRollback();
                                Logger::error("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                                throw new Exception("No se pudo obtener al usuario de la sesion, ya inicio sesion?");
                            }

                            $_direccion->setIdUsuarioUltimaModificacion($id_usuario);                                

                         	//guardamos la direccion

				            try{
								Logger::log("Salvando direccion...");
				                DireccionDAO::save($_direccion);
								
				            }catch(Exception $e){
				                DAO::transRollback();
				                Logger::error("No se pudo guardar la direccion ".$e);
				                if($e->getCode()==901){
									throw new Exception("Error al guardar direccion de la sucursal {$sucursal->getRazonSocial()}: ".$e->getMessage(),901);
								}
				                    
				                throw new Exception("Error al guardar direccion de la sucursal {$sucursal->getRazonSocial()}",901);
				            }                  

                    	}//cambio dir
                }//verificacion editar dir               

            }// !is_null

		}
}
