<?php
/**
  * GET api/pos/client/check_current_client_version
  * Revisar la version que esta actualmente en el servidor
  *
  * Revisar la version que esta actualmente en el servidor. 
  *
  *
  *
  **/

  class ApiPosClientCheckCurrentClientVersion extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::Check_current_client_versionClient( 
 			
		
			
			);
		}catch(Exception $e){
 			Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation() );
		}
 	}
  }
  
  
  
  
  
  
