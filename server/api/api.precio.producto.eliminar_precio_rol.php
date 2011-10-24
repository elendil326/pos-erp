<?php
/**
  * GET api/precio/producto/eliminar_precio_rol
  * Elimina la relacion del precio de un producto con un rol
  *
  * Elimina la relacion del precio de un producto con un rol
  *
  *
  *
  **/

  class ApiPrecioProductoEliminarPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
			"productos" => new ApiExposedProperty("productos", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Eliminar_precio_rolProducto( 
 			
			
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['productos'] ) ? $_GET['productos'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
