<?php
/**
  * GET api/precio/paquete/nuevo_precio_usuario
  * Relaciona un usuario con paquetes a un precio o utilidad 
  *
  * Relaciona un usuario con paquetes a un precio o utilidad 
  *
  *
  *
  **/

  class ApiPrecioPaqueteNuevoPrecioUsuario extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
			"id_usuario" => new ApiExposedProperty("id_usuario", true, GET, array( "int" )),
			"paquetes_precios_utilidad" => new ApiExposedProperty("paquetes_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_usuarioPaquete( 
 			
			
			isset($_GET['id_usuario'] ) ? $_GET['id_usuario'] : null,
			isset($_GET['paquetes_precios_utilidad'] ) ? $_GET['paquetes_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
