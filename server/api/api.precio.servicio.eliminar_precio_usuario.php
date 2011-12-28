<?php
/**
  * GET api/precio/servicio/eliminar_precio_usuario
  * Elimina la relacion del precio de un servicio con un usuario
  *
  * Elimina la relacion del precio de un servicio con un usuario
  *
  *
  *
  **/

  class ApiPrecioServicioEliminarPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "string" )),
			"servicios" => new ApiExposedProperty("servicios", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_usuarioServicio( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['servicios'] ) ? json_decode($_GET['servicios']) : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
