<?php
/**
  * GET api/sesion/lista
  * Obtener las sesiones activas.
  *
  * Obtener las sesiones activas.
  *
  *
  *
  **/

  class ApiSesionLista extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
			"id_grupo" => new ApiExposedProperty("id_grupo", false, GET, array( "int" )),
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = SesionController::Lista( 
 			
			
			isset($_GET['id_grupo'] ) ? $_GET['id_grupo'] : null
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
