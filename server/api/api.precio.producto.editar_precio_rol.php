<?php
/**
  * GET api/precio/producto/editar_precio_rol
  * Edita la relacion de precio de uno o varios productos con un rol
  *
  * Edita la relacion de precio de uno o varios productos con un rol
  *
  *
  *
  **/

  class ApiPrecioProductoEditarPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"productos_precios_utlidad" => new ApiExposedProperty("productos_precios_utlidad", true, GET, array( "json" )),
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Editar_precio_rolProducto( 
 			
			
			isset($_GET['productos_precios_utlidad'] ) ? $_GET['productos_precios_utlidad'] : null,
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
