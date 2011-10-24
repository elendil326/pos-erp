<?php
/**
  * GET api/sesion/cerrar
  * Cerrar la sesion actual.
  *
  * Regresa un url de redireccion seg?l tipo de usuario.
  *
  *
  *
  **/

  class ApiSesionCerrar extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"auth_token" => new ApiExposedProperty("auth_token", false, GET, array( "string" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SesionController::Cerrar( 
 			
			
			isset($_GET['auth_token'] ) ? $_GET['auth_token'] : null
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
