<?php
/**
  * GET api/pos/hash
  * Gerenrar y /o validar un hash
  *
  * Gerenra y /o valida un hash
  *
  *
  *
  **/

  class ApiPosHash extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function CheckAuthorization() {}
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::Hash( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
