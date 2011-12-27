<?php
/**
  * GET api/precio/paquete/eliminar_precio_rol
  * Elimina la relacion del precio de un paquete con un rol
  *
  * Elimina la relacion del precio de un paquete con un rol
  *
  *
  *
  **/

  class ApiPrecioPaqueteEliminarPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
			"paquetes" => new ApiExposedProperty("paquetes", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_rolPaquete( 
 			
			
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['paquetes'] ) ? $_GET['paquetes'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
