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
	
		public static function NuevaDireccionParaUsuario($id_usuario)
		{

			
			$u = UsuarioDAO::getByPK($id_usuario);
			
			if (is_null($u)){
				throw invalidDataException("el $id_usuario no existe");
			}

			if(!is_null($u->getIdDireccion())){
				throw new InvalidDataException("el $id_usuario ya tiene una direccion");
			}


			$did = self::NuevaDireccion("","","","","");
			
			
			DAO::transBegin();
			
			$u->setIdDireccion($did);
			
            try{
                UsuarioDAO::save($u);
            }
            catch(Exception $e)
            {
                DAO::transRollback();
                throw new Exception("No se pudo crear la direccion: ".$e);
            }

            DAO::transEnd();
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
          



            $direccion = new Direccion();

            $id_usuario =  SesionController::Actual(   );
			$id_usuario = $id_usuario["id_usuario"];
			
			
            if($id_usuario == null){	
				Logger::error("SesionController::Actual() regreso null");
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
            $direccion->setUltimaModificacion( time() );
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
							if(array_key_exists('calle',$direccion)){
                            	$_direccion->setCalle( $direccion['calle'] );
								$cambio_direccion = true;
							}                      
                            
                        //numero_exterior                       
							if(array_key_exists('numero_exterior',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setNumeroExterior( $direccion['numero_exterior'] );
							}                   
                            
                        //numero_interior                     
							if(array_key_exists('numero_interior',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setNumeroInterior( $direccion['numero_interior'] );
							}                  
                            
                        //referencia
							if(array_key_exists('referencia',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setReferencia( $direccion['referencia'] );
							}   
                            
                        //colonia             
							if(array_key_exists('colonia',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setColonia( $direccion['colonia'] );
							}                  
                            
                        //id_ciudad                  
							if(array_key_exists('id_ciudad',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setIdCiudad( $direccion['id_ciudad'] );
							}
                                           
                        //codigo_postal                      
							if(array_key_exists('codigo_postal',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setCodigoPostal( $direccion['codigo_postal'] );
							}                  
                            
                        //telefono                    
							if(array_key_exists('telefono',$direccion)){
		                        $cambio_direccion = true;
		                        $_direccion->setTelefono( $direccion['telefono'] );
							}                    
                            
                        //telefono2                
							if(array_key_exists('telefono2',$direccion)){
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
