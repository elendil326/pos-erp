<?php
/**
  * GET api/precio/producto/nuevo_precio_rol
  * Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende
  *
  * Asigna precios y margenes de utilidades a productos de acuerdo al rol del usuario al que se le vende. 
  *
  *
  *
  **/

  class ApiPrecioProductoNuevoPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
			"productos_precios_utlidad" => new ApiExposedProperty("productos_precios_utlidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_rolProducto( 
 			
			
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['productos_precios_utlidad'] ) ? $_GET['productos_precios_utlidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
