<?php
/**
  * GET api/pos/client/download
  * Descargar un zip con la ultima version del cliente
  *
  * Descargar un zip con la ultima version del cliente.
  *
  *
  *
  **/

  class ApiPosClientDownload extends ApiHandler {
  

	protected function DeclareAllowedRoles(){  return BYPASS;  }
	protected function GetRequest()
	{
		$this->request = array(	
		);
	}

	protected function GenerateResponse() {		
		try{
 		$this->response = POSController::DownloadClient( 
 			
		
			
			);
		}catch(Exception $e){
 			//Logger::error($e);
			throw new ApiException( $this->error_dispatcher->invalidDatabaseOperation( $e->getMessage() ) );
		}
 	}
  }
  
  
  
  
  
  
