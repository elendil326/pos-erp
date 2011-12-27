<?php
/**
  * GET api/precio/paquete/eliminar_precio_usuario
  * Elimina la relacion del precio de un paquete con un usuario
  *
  * Elimina la relacion del precio de un paquete con un usuario
  *
  *
  *
  **/

  class ApiPrecioPaqueteEliminarPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"paquetes" => new ApiExposedProperty("paquetes", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_usuarioPaquete( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['paquetes'] ) ? $_GET['paquetes'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
