<?php
/**
  * GET api/sesion/actual
  * Obtener informacion de la sesion actual.
  *
  * Regresa informacion sobre la sesion actual.
  *
  *
  *
  **/

  class ApiSesionActual extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SesionController::Actual( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
