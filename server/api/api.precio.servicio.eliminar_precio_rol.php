<?php
/**
  * GET api/precio/servicio/eliminar_precio_rol
  * Elimina la relacion del precio de un servicio con un rol
  *
  * Elimina la relacion del precio de un servicio con un rol
  *
  *
  *
  **/

  class ApiPrecioServicioEliminarPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
			"servicios" => new ApiExposedProperty("servicios", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_rolServicio( 
 			
			
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['servicios'] ) ? json_decode($_GET['servicios']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
