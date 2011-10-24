<?php
/**
  * GET api/precio/servicio/nuevo_precio_rol
  * Relaciona un rol con productos al precio o utilidad que se le seran vendidos
  *
  * Relaciona un rol con productos al precio o utilidad que se le seran vendidos
  *
  *
  *
  **/

  class ApiPrecioServicioNuevoPrecioRol extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_rol" => new ApiExposedProperty("id_rol", true, GET, array( "int" )),
			"servicios_precios_utilidad" => new ApiExposedProperty("servicios_precios_utilidad", true, GET, array( "json" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = PrecioController::Nuevo_precio_rolServicio( 
 			
			
			isset($_GET['id_rol'] ) ? $_GET['id_rol'] : null,
			isset($_GET['servicios_precios_utilidad'] ) ? $_GET['servicios_precios_utilidad'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
